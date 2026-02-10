import { APP_CONFIG } from "@/src/app.config";
import { CART_KEY, checkAuth, saveCart } from "@/src/helper";
import AsyncStorage from "@react-native-async-storage/async-storage";
import React, { useEffect, useState } from 'react';
import {
  FlatList,
  Image,
  ScrollView,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';

type Product = {
  id: string;
  name: string;
  price: number;
  category: string;
  img: string;
};

export default function Produk() {
  const [searchText, setSearchText] = useState('');
  const [cartCounts, setCartCounts] = useState<{ [key: string]: number }>({});
  const [selectedCategory, setSelectedCategory] = useState('All');
  const [products, setProducts] = useState<Product[]>([]);
  const [categories, setCategories] = useState<string[]>(["All"]);


  const filteredProducts = products.filter(product => {
    const matchCategory = selectedCategory === 'All' || product.category === selectedCategory;
    const matchSearch = product.name.toLowerCase().includes(searchText.toLowerCase());
    return matchCategory && matchSearch;
  });


  const fetchCategories = async () => {
    try {
      const res = await fetch(`${APP_CONFIG.API_URL}/api/kategori`);
      if (!res.ok) throw new Error("Fetch category failed");

      const json = await res.json();

      const mapped = json.data.map((k: any) => k.kategori_nama);

      setCategories(["All", ...mapped]);
    } catch (e) {
      console.error(e);
    }
  };

  const fetchProducts = async () => {
    try {
      const res = await fetch(`${APP_CONFIG.API_URL}/api/alat`);
      if (!res.ok) throw new Error("Fetch product failed");

      const json = await res.json();

      const mapped = json.data.map((item: any) => ({
        id: String(item.alat_id),
        name: item.alat_nama,
        price: item.alat_hargaperhari,
        category: item.kategori?.kategori_nama ?? "Unknown",
        img: APP_CONFIG.IMAGE_BASE_URL + item.photo_path,
      }));

      setProducts(mapped);
    } catch (e) {
      console.error(e);
    }
  };


  const addToCart = async (id: string) => {
    setCartCounts(prev => {
      const updated = {
        ...prev,
        [id]: (prev[id] || 0) + 1,
      };

      saveCart(updated);
      return updated;
    });
  };

  const removeFromCart = async (id: string) => {
    setCartCounts(prev => {
      if (!prev[id]) return prev;

      const updated = {
        ...prev,
        [id]: prev[id] - 1,
      };

      if (updated[id] <= 0) {
        delete updated[id];
      }

      saveCart(updated);
      return updated;
    });
  };

  const loadCart = async () => {
    const cart = await AsyncStorage.getItem(CART_KEY);
    setCartCounts(cart ? JSON.parse(cart) : {});
  };
  
  useEffect(() => {
    const init = async () => {
      await checkAuth();
      await fetchProducts();
      await fetchCategories();
      await loadCart();
    };

    init();
  }, []);


  const renderItem = ({ item }: any) => {
    const count = cartCounts[item.id] || 0;

    return (
      <View style={styles.card}>
        <Image source={item.img} style={styles.image} resizeMode="contain" />
        <Text style={styles.name} numberOfLines={1} ellipsizeMode="tail">{item.name}</Text>

        <View style={styles.cartRow}>
          <TouchableOpacity style={styles.minusButton} onPress={() => removeFromCart(item.id)}>
            <Text style={styles.addText}>-</Text>
          </TouchableOpacity>

          <Text style={styles.countText}>{count}</Text>

          <TouchableOpacity style={styles.addButton} onPress={() => addToCart(item.id)}>
            <Text style={styles.addText}>+</Text>
          </TouchableOpacity>
        </View>

        <Text style={styles.price}>${item.price}</Text>
      </View>
    );
  };

  const totalItems = Object.values(cartCounts).reduce((sum, val) => sum + val, 0);
  const totalPrice = products.reduce((sum, product) => {
    const count = cartCounts[product.id] || 0;
    return sum + product.price * count;
  }, 0);

  return (
    <View style={styles.container}>
      {/* HEADER: kategori + search */}
      <View style={styles.header}>
        {/* Kategori */}
         <Text style={styles.title}>KATEGORI</Text>
        <ScrollView
          horizontal
          showsHorizontalScrollIndicator={false}
          contentContainerStyle={{ paddingHorizontal: 10, alignItems: 'center' }}
          style={{ marginBottom: 10 }}
        >
          {categories.map(cat => (
            <TouchableOpacity
              key={cat}
              style={[
                styles.categoryButton,
                selectedCategory === cat && styles.categoryButtonActive,
              ]}
              onPress={() => setSelectedCategory(cat)}
            >
              <Text
                style={[
                  styles.categoryText,
                  selectedCategory === cat && styles.categoryTextActive,
                ]}
              >
                {cat}
              </Text>
            </TouchableOpacity>
          ))}
        </ScrollView>

        {/* Search */}
        <TextInput
          style={styles.search}
          placeholder="Search..."
          value={searchText}
          onChangeText={setSearchText}
        />
      </View>

      {/* PRODUK */}
      <FlatList
        data={filteredProducts}
        renderItem={renderItem}
        keyExtractor={item => item.id}
        numColumns={2}
        columnWrapperStyle={{ justifyContent: 'space-between', marginBottom: 12 }}
        contentContainerStyle={{ paddingBottom: 80 }} // biar mini cart tidak tertutup
        style={{ flex: 1 }}
      />

      {/* MINI CART */}
      <View style={styles.miniCart}>
        <Text style={styles.miniCartText}>Total Items: {totalItems}</Text>
        <Text style={styles.miniCartText}>Total Price: ${totalPrice}</Text>
      </View>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, backgroundColor: '#4FA3B1', paddingTop: 20 },
  header: { paddingHorizontal: 10, paddingBottom: 10 },
  search: { backgroundColor: '#fff', padding: 12, borderRadius: 10, marginBottom: 10, fontSize: 16 },

  // Tombol kategori
  categoryButton: {
    justifyContent: 'center',
    alignItems: 'center',
    height: 50,
    minWidth: 90,
    paddingHorizontal: 20,
    backgroundColor: '#fff',
    borderRadius: 25,
    marginRight: 10,
  },
  categoryButtonActive: { backgroundColor: '#2FA4B7' },
  categoryText: { fontWeight: 'bold', color: '#333', fontSize: 16 },
  categoryTextActive: { color: '#fff' },

  miniCart: {
    position: 'absolute',
    bottom: 10,
    left: 10,
    right: 10,
    backgroundColor: '#fff',
    padding: 12,
    borderRadius: 10,
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  miniCartText: { fontWeight: 'bold', color: '#333' },

  // CARD
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 12,
    marginBottom: 12,
    alignItems: 'center',
    width: '47%',
  },
  image: { width: 90, height: 90, marginBottom: 8 },
  name: { fontWeight: 'bold', fontSize: 16, flexShrink: 1, textAlign: 'center' },
  price: { marginTop: 5, color: '#333', fontWeight: 'bold' },
  cartRow: { flexDirection: 'row', alignItems: 'center', marginVertical: 5 },
  addButton: { backgroundColor: '#2FA4B7', borderRadius: 50, width: 32, height: 32, justifyContent: 'center', alignItems: 'center' },
  minusButton: { backgroundColor: '#FF6B6B', borderRadius: 50, width: 32, height: 32, justifyContent: 'center', alignItems: 'center', marginRight: 10 },
  addText: { color: '#fff', fontWeight: 'bold', fontSize: 18 },
  countText: { marginHorizontal: 8, fontWeight: 'bold', fontSize: 16 },
  title: { fontWeight: 'bold', color: '#fff', fontSize: 25, marginBottom: 5 },
});
