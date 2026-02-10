import { APP_CONFIG } from '@/src/app.config';
import { checkAuth } from '@/src/helper';
import AsyncStorage from '@react-native-async-storage/async-storage';
import React, { useEffect, useState } from 'react';
import {
  ActivityIndicator,
  Image,
  ScrollView,
  StyleSheet,
  Text,
  View,
} from 'react-native';

export default function RiwayatScreen() {
  const [data, setData] = useState([]);
  const [loading, setLoading] = useState(true);

  const fetchRiwayat = async () => {
    try {
      const pelangganId = await AsyncStorage.getItem('pelanggan_id');

      const res = await fetch(
        `${APP_CONFIG.API_URL}/api/pelanggan/riwayat?pelanggan_id=${pelangganId}`
      );
      const json = await res.json();

      if (json.status) {
        // FLATTEN: 1 alat = 1 card
        const flat = json.data.flatMap((sewa) =>
          sewa.detail.map((d) => ({
            penyewaan_id: sewa.penyewaan_id,
            tgl_sewa: sewa.penyewaan_tglsewa,
            status_bayar: sewa.penyewaan_sttpembayaran,
            status_kembali: sewa.penyewaan_sttkembali,
            alat_nama: d.alat?.alat_nama,
            photo_path: d.alat?.photo_path,
            qty: d.detail_jumlah,
            harga: d.detail_harga,
            total: d.detail_total,
          }))
        );

        setData(flat);
      }
    } catch (e) {
      console.log(e);
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    checkAuth();
    fetchRiwayat();
  }, []);

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Riwayat Sewa</Text>
      <Text style={styles.subtitle}>Daftar Riwayat Pesanan Anda</Text>

      {loading ? (
        <ActivityIndicator size="large" color="#fff" />
      ) : (
        <ScrollView showsVerticalScrollIndicator={false}>
          {data.map((item, index) => (
            <View style={styles.card} key={index}>
              {/* No Resi */}
              <View style={styles.rowBetween}>
                <Text style={styles.label}>NO RESI</Text>
                <Text style={styles.value}>
                  SW-{item.penyewaan_id}
                </Text>
              </View>

              {/* Produk */}
              <View style={styles.productRow}>
                <Image
                  source={{ uri: APP_CONFIG.IMAGE_BASE_URL + item.photo_path }}
                  style={styles.image}
                />
                <View style={{ flex: 1 }}>
                  <Text style={styles.productName}>
                    {item.alat_nama}
                  </Text>
                  <Text style={styles.textSmall}>
                    Tanggal Sewa
                  </Text>
                  <Text style={styles.textSmall}>
                    {item.tgl_sewa}
                  </Text>
                  <Text style={styles.textSmall}>
                    Qty: {item.qty}
                  </Text>
                </View>

                <View style={{ alignItems: 'flex-end' }}>
                  <Text style={styles.textSmall}>Total</Text>
                  <Text style={styles.price}>
                    Rp {item.total.toLocaleString('id-ID')}
                  </Text>
                </View>
              </View>

              {/* Status */}
              <View style={styles.statusRow}>
                <View>
                  <Text style={styles.textSmall}>Pembayaran</Text>
                  <View
                    style={
                      item.status_bayar === 'lunas'
                        ? styles.badgeGreen
                        : styles.badgeRed
                    }
                  >
                    <Text style={styles.badgeText}>
                      {item.status_bayar}
                    </Text>
                  </View>
                </View>

                <View>
                  <Text style={styles.textSmall}>Pengembalian</Text>
                  <View
                    style={
                      item.status_kembali === 'sudah'
                        ? styles.badgeGreen
                        : styles.badgeRed
                    }
                  >
                    <Text style={styles.badgeText}>
                      {item.status_kembali}
                    </Text>
                  </View>
                </View>
              </View>
            </View>
          ))}
        </ScrollView>
      )}
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#2FA4B7', // biru toska background
    padding: 16,
  },
  title: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#fff',
  },
  subtitle: {
    color: '#fff',
    marginBottom: 16,
  },
  card: {
    backgroundColor: '#fff',
    borderRadius: 12,
    padding: 12,
  },
  rowBetween: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    marginBottom: 10,
  },
  label: {
    fontSize: 12,
    color: '#888',
  },
  value: {
    fontSize: 12,
    fontWeight: 'bold',
  },
  productRow: {
    flexDirection: 'row',
    gap: 10,
    marginBottom: 12,
  },
  image: {
    width: 50,
    height: 50,
    borderRadius: 8,
  },
  productName: {
    fontWeight: 'bold',
    marginBottom: 4,
  },
  textSmall: {
    fontSize: 12,
    color: '#777',
  },
  price: {
    fontWeight: 'bold',
    marginTop: 4,
  },
  statusRow: {
    flexDirection: 'row',
    justifyContent: 'space-between',
  },
  badgeRed: {
      backgroundColor: '#E74C3C',
      paddingHorizontal: 12,
      paddingVertical: 6,
      borderRadius: 8,
      marginTop: 6,

      shadowColor: '#E74C3C',
      shadowOffset: { width: 0, height: 4 },
      shadowOpacity: 0.35,
      shadowRadius: 6,
      elevation: 5,
  },
  
  badgeText: {
    color: '#fff',
    fontSize: 12,
    fontWeight: 'bold',
  },
});
