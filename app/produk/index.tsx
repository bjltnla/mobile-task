import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TextInput,
  TouchableOpacity,
  Image,
  FlatList,
  ScrollView,
} from 'react-native';

const productsData = [
  { id: '1', name: 'IPHONE', price: 50000, category: 'HP', img: require('../../assets/images/iphone.jpg') },
  { id: '2', name: 'REDMI', price: 40000, category: 'HP', img: require('../../assets/images/redmi.jpg') },
  { id: '3', name: 'OPPO', price: 45000, category: 'HP', img: require('../../assets/images/oppo.jpg') },
  { id: '4', name: 'SAMSUNG', price: 55000, category: 'HP', img: require('../../assets/images/samsung.jpg') },
  { id: '5', name: 'LENOVO', price: 100000, category: 'Laptop', img: require('../../assets/images/laptop.jpg') },
  { id: '6', name: 'DELL', price: 120000, category: 'Laptop', img: require('../../assets/images/laptop2.jpg') },
  { id: '7', name: 'CANON', price: 75000, category: 'Camera', img: require('../../assets/images/canon.jpg') },
  { id: '8', name: 'NIKON', price: 80000, category: 'Camera', img: require('../../assets/images/nikon.jpg') },
];

const categories = ['All', 'HP', 'Laptop', 'Camera'];

export default function Produk() {
  const [searchText, setSearchText] = useState('');
  const [cartCounts, setCartCounts] = useState<{ [key: string]: number }>({});
  const [selectedCategory, setSelectedCategory] = useState('All');

  const filteredProducts = productsData.filter(product => {
    const matchCategory = selectedCategory === 'All' || product.category === selectedCategory;
    const matchSearch = product.name.toLowerCase().includes(searchText.toLowerCase());
    return matchCategory && matchSearch;
  });

  const addToCart = (id: string) => {
    setCartCounts(prev => ({
      ...prev,
      [id]: prev[id] ? prev[id] + 1 : 1,
    }));
  };

  const removeFromCart = (id: string) => {
    setCartCounts(prev => {
      if (!prev[id] || prev[id] <= 0) return prev;
      return { ...prev, [id]: prev[id] - 1 };
    });
  };

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
  const totalPrice = productsData.reduce((sum, product) => {
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
