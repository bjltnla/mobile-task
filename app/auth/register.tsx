import { APP_CONFIG } from "@/src/app.config";
import { useRouter } from "expo-router";
import { useState } from "react";
import { StyleSheet, Text, TextInput, TouchableOpacity, View } from "react-native";

export default function Register() {
  const router = useRouter();

  const [name, setName] = useState("");
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [address, setAddress] = useState("");
  const [password, setPassword] = useState("");

  const handleRegister = async () => {
    const payload = {
      pelanggan_nama: name,
      pelanggan_email: email,
      pelanggan_notelp: phone,
      pelanggan_alamat: address,
      pelanggan_password: password,
    };

    try {
      const response = await fetch(`${APP_CONFIG.API_URL}/api/pelanggan/register`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify(payload),
      });

      const result = await response.json();

      if (!response.ok) {
        alert(result.message || "Register gagal");
        return;
      }

      router.replace("/auth/Login");
    } catch (error) {
      console.log(error);
      alert("Tidak bisa connect ke server");
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Register</Text>

      <TextInput placeholder="Nama Lengkap" style={styles.input} onChangeText={setName} />
      <TextInput placeholder="Email" style={styles.input} onChangeText={setEmail} />
      <TextInput placeholder="No Telp" style={styles.input} onChangeText={setPhone} />
      <TextInput placeholder="Alamat" style={styles.input} onChangeText={setAddress} />
      <TextInput placeholder="Password" secureTextEntry style={styles.input} onChangeText={setPassword} />

      <TouchableOpacity style={styles.button} onPress={handleRegister}>
        <Text style={styles.buttonText}>DAFTAR</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: { flex: 1, padding: 20, backgroundColor: "#EAF6F8" },
  title: { fontSize: 24, fontWeight: "bold", marginBottom: 20, textAlign: "center" },
  input: { backgroundColor: "#fff", padding: 14, borderRadius: 10, marginBottom: 12 },
  button: { backgroundColor: "#2FA4B7", padding: 15, borderRadius: 10, alignItems: "center" },
  buttonText: { color: "#fff", fontWeight: "bold" },
});
