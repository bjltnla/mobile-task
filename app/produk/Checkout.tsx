import React from 'react';
import { View, Text, StyleSheet } from 'react-native';

export default function Checkout() {
  return (
    <View style={styles.container}>
      <Text style={styles.title}>Checkout</Text>
      <Text style={styles.text}>Disini akan muncul semua item yang ada di cart</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, justifyContent: 'center', alignItems: 'center', backgroundColor: '#4FA3B1' },
  title: { fontSize: 24, fontWeight: 'bold', color: '#fff', marginBottom: 10 },
  text: { color: '#fff', fontSize: 16 },
});
