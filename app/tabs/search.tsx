import { APP_CONFIG } from "@/src/app.config";
import { checkAuth } from "@/src/helper";
import React, { useEffect, useState } from 'react';
import {
  Dimensions,
  FlatList,
  Image,
  StyleSheet,
  Text,
  TextInput,
  View,
} from 'react-native';

type ApiProduct = {
  alat_id: number;
  alat_nama: string;
  alat_hargaperhari: number;
  photo_path: string;
  kategori?: {
    kategori_nama: string;
  };
};

type Product = {
  id: string;
  name: string;
  price: number;
  category: string;
  img: string;
};


const width = Dimensions.get('window').width;

export default function SearchScreen() {
  const [query, setQuery] = useState('');
  const [products, setProducts] = useState<Product[]>([]);
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    checkAuth();
    const fetchProducts = async () => {
      setLoading(true);
      try {
        const res = await fetch(`${APP_CONFIG.API_URL}/api/alat`);
        if (!res.ok) throw new Error("Fetch failed");

        const json = await res.json();

        const mapped: Product[] = json.data.map((item: ApiProduct) => ({
          id: String(item.alat_id),
          name: item.alat_nama,
          img: APP_CONFIG.IMAGE_BASE_URL + item.photo_path,
        }));

        setProducts(mapped);
      } catch (e) {
        console.error(e);
      } finally {
        setLoading(false);
      }
    };

    fetchProducts();
  }, []);

  const filtered = products.filter(item =>
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
        ListEmptyComponent={
          !loading ? <Text style={{ textAlign: 'center' }}>No data</Text> : null
        }
        renderItem={({ item }) => (
          <View style={styles.card}>
            <Image source={{ uri: item.img }} style={styles.img} />
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
