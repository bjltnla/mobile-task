import React, { useState } from 'react';
import {
  View,
  Text,
  StyleSheet,
  TextInput,
  TouchableOpacity,
} from 'react-native';

export default function ProfileScreen() {
  const [mode, setMode] = useState<'view' | 'edit'>('view');

  // 🔥 DATA DARI LOGIN (contoh)
  const [nama, setNama] = useState('Ian Fajar');
  const [email, setEmail] = useState('Ian@gmail.com');
  const [phone, setPhone] = useState('08123456789');
  const [alamat, setAlamat] = useState('Jl. Danau Jonge');
  const [password, setPassword] = useState('100107');

  return (
    <View style={styles.container}>
      {/* Header */}
      <View style={styles.header}>
        <View style={styles.avatar} />
        <Text style={styles.name}>Profil</Text>
        <Text style={styles.email}>{email}</Text>
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
          value={nama}
          editable={mode === 'edit'}
          onChangeText={setNama}
        />
        <TextInput
          style={styles.input}
          value={email}
          editable={mode === 'edit'}
          onChangeText={setEmail}
        />
        <TextInput
          style={styles.input}
          value={phone}
          editable={mode === 'edit'}
          onChangeText={setPhone}
        />
        <TextInput
          style={styles.input}
          value={alamat}
          editable={mode === 'edit'}
          onChangeText={setAlamat}
        />
        <TextInput
          style={styles.input}
          value={password}
          secureTextEntry
          editable={mode === 'edit'}
          onChangeText={setPassword}
        />
      </View>

      {/* Button */}
      {mode === 'edit' && (
        <TouchableOpacity style={styles.button}>
          <Text style={styles.buttonText}>Simpan Perubahan</Text>
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
