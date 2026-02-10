import { APP_CONFIG } from '@/src/app.config';
import { CART_KEY, checkAuth, loadCart, saveCart } from '@/src/helper';
import { Ionicons } from '@expo/vector-icons';
import AsyncStorage from '@react-native-async-storage/async-storage';
import React, { useEffect, useState } from 'react';
import {
  Alert,
  FlatList,
  Image,
  StyleSheet,
  Text,
  TouchableOpacity,
  View,
} from 'react-native';

type Product = {
  id: string;
  name: string;
  price: number;
  img: string;
};

type CartItem = Product & { qty: number };

export default function KeranjangScreen() {
  const [items, setItems] = useState<CartItem[]>([]);

  useEffect(() => {
    const loadData = async () => {
      try {
        // 1. load cart
        const cart = await AsyncStorage.getItem(CART_KEY);
        const cartCounts: Record<string, number> = cart
          ? JSON.parse(cart)
          : {};

        if (Object.keys(cartCounts).length === 0) {
          setItems([]);
          return;
        }

        // 2. fetch products
        const res = await fetch(`${APP_CONFIG.API_URL}/api/alat`);
        const json = await res.json();

        // 3. map cart → product
        const mapped: CartItem[] = json.data
          .filter((p: any) => cartCounts[p.alat_id])
          .map((p: any) => ({
            id: String(p.alat_id),
            name: p.alat_nama,
            price: p.alat_hargaperhari,
            img: APP_CONFIG.IMAGE_BASE_URL + p.photo_path,
            qty: cartCounts[p.alat_id],
          }));

        setItems(mapped);
      } catch (e) {
        console.error(e);
      }
    };

    loadData();
  }, []);

  const sewa = async () => {
    try {
      const currentDate = new Date();
      const formattedDate = currentDate.toISOString().slice(0, 10);

      const res = await fetch(`${APP_CONFIG.API_URL}/api/pelanggan/sewa`, {
        method: "POST",
        headers: {
          Accept: 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          data: {
              penyewaan_pelanggan_id: await AsyncStorage.getItem('pelanggan_id'),
              penyewaan_tglsewa: formattedDate,
              penyewaan_tglkembali: formattedDate,
              penyewaan_totalharga: totalPrice,
          },
          items: items
            .map((p: any) => ({
              id: p.id,
              price: p.price,
              qty: p.qty,
              total: p.qty * p.price
            })),
        }),
      });

      const json = await res.json();

      if (!json.status) {
        Alert.alert('Error', json.message || 'Gagal simpan profil');
        return;
      }

      Alert.alert('Berhasil', 'Sewa berhasil');

      saveCart({})
      setItems([]);
    } catch {
      Alert.alert('Error', 'Gagal koneksi ke server');
    }
  };

  const removeItem = async (id: string) => {
    const updated = items.filter(item => item.id !== id);
    setItems(updated);

    const cart = await loadCart();
    delete cart[id];
    await AsyncStorage.setItem(CART_KEY, JSON.stringify(cart));
  };

  const totalPrice = items.reduce(
    (sum, item) => sum + item.price * item.qty,
    0
  );

  useEffect(() => {
    checkAuth();
  })

  return (
    <View style={styles.container}>
      <Text style={styles.title}>KERANJANG</Text>

      <FlatList
        data={items}
        keyExtractor={item => item.id}
        ListEmptyComponent={
          <Text style={{ color: '#fff', textAlign: 'center' }}>
            Keranjang kosong
          </Text>
        }
        renderItem={({ item }) => (
          <View style={styles.card}>
            <Image source={{ uri: item.img }} style={styles.image} />

            <View style={{ flex: 1 }}>
              <Text style={styles.productName}>{item.name}</Text>
              <Text style={styles.price}>
                Rp. {item.price} x {item.qty}
              </Text>
            </View>

            <TouchableOpacity onPress={() => removeItem(item.id)}>
              <Ionicons name="trash-outline" size={22} color="red" />
            </TouchableOpacity>
          </View>
        )}
      />

      {/* Footer */}
      <View style={styles.footer}>
        <View>
          <Text style={styles.totalText}>Total Pembayaran</Text>
          <Text style={styles.totalPrice}>Rp. {totalPrice}</Text>
        </View>

        <TouchableOpacity style={styles.button}>
          <Text style={styles.buttonText} onPress={sewa}>Sewa Sekarang</Text>
        </TouchableOpacity>
      </View>
    </View>
  );
}


const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#2FA4B7',
    padding: 16,
  },
  title: {
    color: '#fff',
    fontSize: 18,
    fontWeight: 'bold',
    marginBottom: 16,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 12,
    flexDirection: 'row',
    alignItems: 'center',
    gap: 10,

    shadowColor: '#000',
    shadowOffset: { width: 0, height: 3 },
    shadowOpacity: 0.15,
    shadowRadius: 4,
    elevation: 4,
  },
  image: {
    width: 50,
    height: 50,
    borderRadius: 8,
    backgroundColor: '#ddd',
  },
  productName: {
    fontWeight: 'bold',
  },
  price: {
    fontSize: 12,
    color: '#777',
  },
  footer: {
    marginTop: 'auto',
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
  },
  totalText: {
    color: '#fff',
    fontSize: 12,
  },
  totalPrice: {
    color: '#fff',
    fontWeight: 'bold',
  },
  button: {
    backgroundColor: '#fff',
    paddingHorizontal: 18,
    paddingVertical: 10,
    borderRadius: 8,
  },
  buttonText: {
    color: '#2FA4B7',
    fontWeight: 'bold',
  },
});
