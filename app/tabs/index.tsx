import { View, Text, StyleSheet, TouchableOpacity} from 'react-native';
import { router } from 'expo-router';
import { useEffect, useState } from 'react';
import AsyncStorage from '@react-native-async-storage/async-storage';

export default function Dashboard() {
  const [username, setUsername] = useState('');

  useEffect(() => {
    const getUser = async () => {
      const name = await AsyncStorage.getItem('username');
      if (name) setUsername(name);
    };
    getUser();
  }, []);

  return (
    <View style={styles.container}>

      {/* HEADER */}
      <View style={styles.header}>
        <View>
          <Text style={styles.greeting}>Halo, selamat datang 👋</Text>
          <Text style={styles.username}>{username || 'User'}</Text>
        </View>
        <View style={styles.avatar} />
      </View>

      {/* MENU */}
      <View style={styles.menuContainer}>
        <TouchableOpacity
        style={styles.menuItem}
        onPress={() => router.push('/produk')}
        >
          <Text style={styles.menuIcon}>📦</Text>
          <Text style={styles.menuText}>Produk</Text>
          </TouchableOpacity>
      </View>

    </View>
  );
}
const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F4F9FB',
    padding: 20,
  },

  /* HEADER */
  header: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    marginBottom: 20,
  },

  greeting: {
    fontSize: 14,
    color: '#666',
  },

  username: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#000',
  },

  avatar: {
    width: 44,
    height: 44,
    borderRadius: 22,
    backgroundColor: '#ddd',
  },

  searchInput: {
    height: 45,
    backgroundColor: '#fff',
    borderRadius: 12,
    paddingHorizontal: 16,
    fontSize: 14,
    elevation: 2, // shadow Android
  },

  /* MENU */
  menuContainer: {
    flexDirection: 'row',
  },

  menuItem: {
    width: '100%',              // ⬅️ full ke samping
    backgroundColor: '#fff',
    borderRadius: 16,
    paddingVertical: 20,
    paddingHorizontal: 20,
    flexDirection: 'row',       // ⬅️ icon + text sejajar
    alignItems: 'center',
    elevation: 2,
  },

  menuIcon: {
    fontSize: 24,
    marginRight: 12,
  },

  menuText: {
    fontSize: 16,
    fontWeight: '600',
    color: '#000',
  },
});
