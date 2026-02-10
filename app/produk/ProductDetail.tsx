import React, { useState } from 'react';
import { View, Text, StyleSheet, Image, TouchableOpacity, ScrollView } from 'react-native';

export default function ProductDetail({ route, navigation }: any) {
  const { product } = route.params;
  const [selectedModel, setSelectedModel] = useState(product.models[0]);
  const [selectedRam, setSelectedRam] = useState(product.ram[0]);
  const [quantity, setQuantity] = useState(1);

  const addToCart = () => {
    alert(`Added ${quantity} ${product.name} ${selectedModel} RAM:${selectedRam} to cart`);
  };

  return (
    <ScrollView style={styles.container}>
      <Image source={product.img} style={styles.image} resizeMode="contain" />
      <Text style={styles.name}>{product.name}</Text>
      <Text style={styles.price}>${product.price}</Text>

      <Text style={styles.optionTitle}>Model</Text>
      {product.models.map((m: string) => (
        <TouchableOpacity
          key={m}
          style={[styles.optionButton, selectedModel === m && styles.optionButtonActive]}
          onPress={() => setSelectedModel(m)}
        >
          <Text style={[styles.optionText, selectedModel === m && styles.optionTextActive]}>{m}</Text>
        </TouchableOpacity>
      ))}

      <Text style={styles.optionTitle}>RAM</Text>
      {product.ram.map((r: string) => (
        <TouchableOpacity
          key={r}
          style={[styles.optionButton, selectedRam === r && styles.optionButtonActive]}
          onPress={() => setSelectedRam(r)}
        >
          <Text style={[styles.optionText, selectedRam === r && styles.optionTextActive]}>{r}</Text>
        </TouchableOpacity>
      ))}

      <Text style={styles.optionTitle}>Quantity</Text>
      <View style={styles.cartRow}>
        <TouchableOpacity style={styles.minusButton} onPress={() => setQuantity(q => Math.max(1, q - 1))}>
          <Text style={styles.addText}>-</Text>
        </TouchableOpacity>
        <Text style={styles.countText}>{quantity}</Text>
        <TouchableOpacity style={styles.addButton} onPress={() => setQuantity(q => q + 1)}>
          <Text style={styles.addText}>+</Text>
        </TouchableOpacity>
      </View>

      <TouchableOpacity style={styles.addToCartButton} onPress={addToCart}>
        <Text style={styles.addToCartText}>Add to Cart</Text>
      </TouchableOpacity>

      <TouchableOpacity style={styles.checkoutButton} onPress={() => navigation.navigate('Checkout')}>
        <Text style={styles.checkoutText}>Go to Checkout</Text>
      </TouchableOpacity>
    </ScrollView>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, backgroundColor: '#4FA3B1' },
  image: { width: '100%', height: 200, marginBottom: 10 },
  name: { fontSize: 20, fontWeight: 'bold', color: '#fff' },
  price: { fontSize: 18, fontWeight: 'bold', color: '#fff', marginBottom: 10 },
  optionTitle: { fontWeight: 'bold', fontSize: 16, color: '#fff', marginTop: 10 },
  optionButton: { padding: 8, backgroundColor: '#fff', borderRadius: 8, marginTop: 5 },
  optionButtonActive: { backgroundColor: '#2FA4B7' },
  optionText: { fontWeight: 'bold', color: '#333' },
  optionTextActive: { color: '#fff' },
  cartRow: { flexDirection: 'row', alignItems: 'center', marginVertical: 10 },
  addButton: { backgroundColor: '#2FA4B7', borderRadius: 50, width: 30, height: 30, justifyContent: 'center', alignItems: 'center' },
  minusButton: { backgroundColor: '#FF6B6B', borderRadius: 50, width: 30, height: 30, justifyContent: 'center', alignItems: 'center', marginRight: 10 },
  addText: { color: '#fff', fontWeight: 'bold', fontSize: 18 },
  countText: { marginHorizontal: 8, fontWeight: 'bold', fontSize: 16, color: '#fff' },
  addToCartButton: { marginTop: 20, backgroundColor: '#2FA4B7', padding: 15, borderRadius: 10, alignItems: 'center' },
  addToCartText: { color: '#fff', fontWeight: 'bold', fontSize: 16 },
  checkoutButton: { marginTop: 10, backgroundColor: '#FF6B6B', padding: 15, borderRadius: 10, alignItems: 'center' },
  checkoutText: { color: '#fff', fontWeight: 'bold', fontSize: 16 },
});
