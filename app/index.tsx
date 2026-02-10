import { View, Text, Button } from 'react-native';
import { useState } from 'react';

export default function Home() {
  const [data, setData] = useState('');

  const getData = async () => {
    try {
      const res = await fetch('http://192.168.1.10:8000/api/test');
      const json = await res.json();
      setData(json.message);
    } catch (err) {
      console.log(err);
    }
  };

  return (
    <View style={{ padding: 20 }}>
      <Button title="Get API" onPress={getData} />
      <Text>{data}</Text>
    </View>
  );
}
