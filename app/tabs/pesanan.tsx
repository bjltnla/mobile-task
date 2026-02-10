import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  Image,
  ScrollView,
} from 'react-native';

export default function RiwayatScreen() {
  return (
    <View style={styles.container}>
      {/* Header */}
      <Text style={styles.title}>Riwayat Sewa</Text>
      <Text style={styles.subtitle}>Daftar Riwayat Pesanan Anda</Text>

      <ScrollView showsVerticalScrollIndicator={false}>
        {/* Card Riwayat */}
        <View style={styles.card}>
          {/* No Resi */}
          <View style={styles.rowBetween}>
            <Text style={styles.label}>NO RESI</Text>
            <Text style={styles.value}>AB-12</Text>
          </View>

          {/* Produk */}
          <View style={styles.productRow}>
            <Image
              source={require('../../assets/images/iphone.jpg')}
              style={styles.image}
            />
            <View style={{ flex: 1 }}>
              <Text style={styles.productName}>iPhone</Text>
              <Text style={styles.textSmall}>Tanggal Sewa</Text>
              <Text style={styles.textSmall}>29 Jan 2026</Text>
            </View>
            <View style={{ alignItems: 'flex-end' }}>
              <Text style={styles.textSmall}>Total Harga</Text>
              <Text style={styles.price}>Rp 30.000</Text>
            </View>
          </View>

          {/* Status */}
          <View style={styles.statusRow}>
            <View>
              <Text style={styles.textSmall}>Pembayaran</Text>
              <View style={styles.badgeRed}>
                <Text style={styles.badgeText}>Belum Bayar</Text>
              </View>
            </View>

            <View>
              <Text style={styles.textSmall}>Pengembalian</Text>
              <View style={styles.badgeRed}>
                <Text style={styles.badgeText}>Belum Kembali</Text>
              </View>
            </View>
          </View>
        </View>
      </ScrollView>
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
