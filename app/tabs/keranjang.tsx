import React from 'react';
import {
  View,
  Text,
  StyleSheet,
  Image,
  TouchableOpacity,
} from 'react-native';
import { Ionicons } from '@expo/vector-icons';

export default function KeranjangScreen() {
  return (
    <View style={styles.container}>
      <Text style={styles.title}>KERANJANG</Text>

      {/* Card Produk */}
      <View style={styles.card}>
        <Image
          source={require('../../assets/images/iphone.jpg')}
          style={styles.image}
        />

        <View style={{ flex: 1 }}>
          <Text style={styles.productName}>Iphone</Text>
          <Text style={styles.price}>Rp. 30.000</Text>
        </View>

        <TouchableOpacity>
          <Ionicons name="trash-outline" size={22} color="red" />
        </TouchableOpacity>
      </View>

      {/* Footer */}
      <View style={styles.footer}>
        <View>
          <Text style={styles.totalText}>Total Pembayaran</Text>
          <Text style={styles.totalPrice}>Rp. 30.000</Text>
        </View>

        <TouchableOpacity style={styles.button}>
          <Text style={styles.buttonText}>Sewa Sekarang</Text>
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
