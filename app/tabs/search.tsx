import React, { useState } from 'react';
import { View, TextInput, FlatList, Text, Image, StyleSheet, Dimensions } from 'react-native';

type Product = {
  id: string;
  name: string;
  img: any;
};

const data: Product[] = [
  { id: '1', name: 'iPhone', img: require('../../assets/images/iphone.jpg') },
  { id: '2', name: 'Oppo', img: require('../../assets/images/oppo.jpg') },
];

const width = Dimensions.get('window').width;

export default function SearchScreen() {
  const [query, setQuery] = useState('');

  const filtered = data.filter(item =>
    item.name.toLowerCase().includes(query.toLowerCase())
  );

  return (
    <View style={styles.container}>
      <TextInput
        placeholder="Cari produk..."
        style={styles.input}
        value={query}
        onChangeText={setQuery}
      />

      <FlatList
        data={filtered}
        numColumns={2}
        keyExtractor={item => item.id}
        renderItem={({ item }) => (
          <View style={styles.card}>
            <Image source={item.img} style={styles.img} />
            <Text>{item.name}</Text>
          </View>
        )}
      />
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 16 },
  input: {
    backgroundColor: '#fff',
    padding: 12,
    borderRadius: 10,
    marginBottom: 16,
  },
  card: {
    width: width / 2 - 24,
    backgroundColor: '#fff',
    margin: 8,
    borderRadius: 12,
    padding: 10,
  },
  img: { width: '100%', height: 100, borderRadius: 10 },
});
