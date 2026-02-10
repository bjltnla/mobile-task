import { APP_CONFIG } from "@/src/app.config";
import AsyncStorage from "@react-native-async-storage/async-storage";
import { useRouter } from "expo-router";
import { useState } from "react";
import { StyleSheet, Text, TextInput, TouchableOpacity, View } from "react-native";

export default function Login() {
  const router = useRouter();
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [loading, setLoading] = useState(false);

  const handleLogin = async () => {
    if (loading) return;

    setLoading(true);

    try {
      const response = await fetch(`${APP_CONFIG.API_URL}/api/pelanggan/login`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          pelanggan_email: email,
          pelanggan_password: password,
        }),
      });

      const result = await response.json();

      if (!response.ok) {
        alert(result.message || "Login gagal");
        setLoading(false);
        return;
      }

      await AsyncStorage.setItem("token", result.token);
      await AsyncStorage.setItem("username", result.data.pelanggan_nama);
      await AsyncStorage.setItem("pelanggan_id", result.data.pelanggan_id);
      await AsyncStorage.setItem("isLogin", "true");

      router.replace("/tabs");
    } catch (error) {
      console.log(error);
      alert("Tidak bisa connect ke server");
    } finally {
      setLoading(false);
    }
  };

  return (
    <View style={styles.container}>
      <Text style={styles.title}>Login</Text>

      <TextInput placeholder="Email" style={styles.input} value={email} onChangeText={setEmail} />

      <TextInput placeholder="Password" secureTextEntry style={styles.input} value={password} onChangeText={setPassword} />

      <TouchableOpacity style={styles.button} onPress={handleLogin} disabled={loading}>
        <Text style={styles.buttonText}>LOGIN</Text>
      </TouchableOpacity>

      <TouchableOpacity onPress={() => router.push("/auth/register")}>
        <Text style={styles.link}>Daftar Akun</Text>
      </TouchableOpacity>
    </View>
  );
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: "#EAF6F8",
    justifyContent: "center",
    padding: 20,
  },
  title: {
    fontSize: 26,
    fontWeight: "bold",
    marginBottom: 20,
    textAlign: "center",
  },
  input: {
    backgroundColor: "#fff",
    padding: 14,
    borderRadius: 10,
    marginBottom: 15,
  },
  button: {
    backgroundColor: "#2FA4B7",
    padding: 15,
    borderRadius: 10,
    alignItems: "center",
  },
  buttonText: {
    color: "#fff",
    fontWeight: "bold",
  },
  link: {
    marginTop: 20,
    textAlign: "center",
    color: "#2FA4B7",
    fontWeight: "bold",
  },
});
