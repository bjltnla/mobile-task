import { View, Text, StyleSheet } from 'react-native';

type Props = {
  name: string;
};

export default function ProductCard({ name }: Props) {
  return (
    <View style={styles.card}>
      <Text style={styles.text}>{name}</Text>
    </View>
  );
}

const styles = StyleSheet.create({
  card: {
    backgroundColor: '#fff',
    padding: 18,
    borderRadius: 14,
    marginBottom: 12,
  },
  text: {
    fontSize: 16,
    fontWeight: '500',
  },
});
