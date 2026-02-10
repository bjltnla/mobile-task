import { APP_CONFIG } from '@/src/app.config';
import AsyncStorage from '@react-native-async-storage/async-storage';
import { router } from 'expo-router';
import React, { useEffect, useState } from 'react';
import {
  ActivityIndicator,
  Alert,
  StyleSheet,
  Text,
  TextInput,
  TouchableOpacity,
  View,
} from 'react-native';

type Pelanggan = {
  pelanggan_id?: number;
  pelanggan_nama: string;
  pelanggan_email: string;
  pelanggan_notelp: string;
  pelanggan_alamat: string;
  pelanggan_password: string;
  photo_path?: string;
};

export default function ProfileScreen() {
  const [mode, setMode] = useState<'view' | 'edit'>('view');
  const [loading, setLoading] = useState(true);
  const [user, setUser] = useState<Pelanggan | null>(null);

  const loadProfile = async () => {
    try {
      setLoading(true);

      const token = await AsyncStorage.getItem('token');

      // 1. Token tidak ada → langsung ke login
      if (!token) {
        router.replace('/auth/Login');
        return;
      }

      const res = await fetch(`${APP_CONFIG.API_URL}/api/pelanggan/me`, {
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
        },
      });

      // 2. JWT invalid / expired
      if (res.status === 401) {
        await AsyncStorage.multiRemove(['token', 'pelanggan_id']);
        router.replace('/auth/Login');
        return;
      }

      const json = await res.json();

      // 3. Backend error logic
      if (!json.status) {
        Alert.alert('Error', json.message || 'Gagal load profil');
        return;
      }

      setUser(json.data);
    } catch (e) {
      Alert.alert('Error', 'Gagal koneksi ke server');
    } finally {
      setLoading(false);
    }
  };

  const saveProfile = async () => {
    try {
      const token = await AsyncStorage.getItem('token');
      if (!token) {
        Alert.alert('Error', 'Token tidak ditemukan');
        return;
      }

      const res = await fetch(`${APP_CONFIG.API_URL}/api/pelanggan/me/update/${user?.pelanggan_id}`, {
        method: "POST",
        headers: {
          Authorization: `Bearer ${token}`,
          Accept: 'application/json',
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          pelanggan_nama: user?.pelanggan_nama,
          pelanggan_email: user?.pelanggan_email,
          pelanggan_notelp: user?.pelanggan_notelp,
          pelanggan_alamat: user?.pelanggan_alamat,
          pelanggan_password: user?.pelanggan_password,
      }),
      });

      const json = await res.json();

      if (!json.status) {
        Alert.alert('Error', json.message || 'Gagal simpan profil');
        return;
      }

      setUser(json.data);
    } catch {
      Alert.alert('Error', 'Gagal koneksi ke server');
    } finally {
      setLoading(false);
    }
  };

  useEffect(() => {
    loadProfile();
  }, []);

  if (loading) {
    return (
      <View style={styles.container}>
        <ActivityIndicator size="large" color="#fff" />
      </View>
    );
  }

  if (!user) return null;

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <View style={styles.avatar} />
        <Text style={styles.name}>{user.pelanggan_nama}</Text>
        <Text style={styles.email}>{user.pelanggan_email}</Text>
      </View>

      {/* Tabs */}
      <View style={styles.tab}>
        <TouchableOpacity onPress={() => setMode('view')}>
          <Text style={mode === 'view' ? styles.tabActive : styles.tabInactive}>
            Data Diri
          </Text>
        </TouchableOpacity>

        <TouchableOpacity onPress={() => setMode('edit')}>
          <Text style={mode === 'edit' ? styles.tabActive : styles.tabInactive}>
            Ubah Data
          </Text>
        </TouchableOpacity>
      </View>

      {/* Form */}
      <View style={styles.form}>
        <TextInput
          style={styles.input}
          value={user.pelanggan_nama}
          editable={mode === 'edit'}
          onChangeText={t => setUser({ ...user, pelanggan_nama: t })}
        />

        <TextInput
          style={styles.input}
          value={user.pelanggan_email}
          editable={mode === 'edit'}
          onChangeText={t => setUser({ ...user, pelanggan_email: t })}
        />

        <TextInput
          style={styles.input}
          value={user.pelanggan_notelp}
          editable={mode === 'edit'}
          onChangeText={t => setUser({ ...user, pelanggan_notelp: t })}
        />

        <TextInput
          style={styles.input}
          value={user.pelanggan_alamat}
          editable={mode === 'edit'}
          onChangeText={t => setUser({ ...user, pelanggan_alamat: t })}
        />

        {/* 🔒 PASSWORD */}
        <TextInput
          style={styles.input}
          secureTextEntry
          editable={mode === 'edit'}
          placeholder='password'
          onChangeText={t => setUser({ ...user, pelanggan_password: t })}
        />
      </View>

      {/* Button */}
      {mode === 'edit' && (
        <TouchableOpacity style={styles.button}>
          <Text style={styles.buttonText} onPress={saveProfile}>Simpan Perubahan</Text>
        </TouchableOpacity>
      )}
    </View>
  );
}


const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#3AA1B0',
    padding: 16,
  },

  header: {
    alignItems: 'center',
    marginBottom: 16,
  },

  avatar: {
    width: 80,
    height: 80,
    borderRadius: 40,
    backgroundColor: '#000',
    marginBottom: 8,
  },

  name: {
    fontSize: 18,
    fontWeight: 'bold',
    color: '#fff',
  },

  email: {
    fontSize: 12,
    color: '#E0E0E0',
  },

  tab: {
    flexDirection: 'row',
    justifyContent: 'space-around',
    marginVertical: 12,
  },

  tabActive: {
    fontWeight: 'bold',
    color: '#000',
    borderBottomWidth: 2,
    borderBottomColor: '#000',
    paddingBottom: 4,
  },

  tabInactive: {
    color: '#666',
  },

  form: {
    gap: 12,
  },

  input: {
    backgroundColor: '#fff',
    borderRadius: 10,
    paddingVertical: 12,
    paddingHorizontal: 14,

    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.15,
    shadowRadius: 4,
    elevation: 3,
  },

  button: {
    marginTop: 20,
    backgroundColor: '#2E7D8A',
    paddingVertical: 14,
    borderRadius: 10,
  },

  buttonText: {
    color: '#fff',
    textAlign: 'center',
    fontWeight: 'bold',
  },
});
