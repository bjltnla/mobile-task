import AsyncStorage from '@react-native-async-storage/async-storage';
import { router } from 'expo-router';
import { Alert } from 'react-native';
import { APP_CONFIG } from './app.config';

export const CART_KEY = 'cart_counts';

export const saveCart = async (cart: Record<string, number>) => {
  try {
    await AsyncStorage.setItem(CART_KEY, JSON.stringify(cart));
  } catch (e) {
    console.error('Failed save cart', e);
  }
};

export const loadCart = async () => {
  try {
    const stored = await AsyncStorage.getItem(CART_KEY);
    return stored ? JSON.parse(stored) : {};
  } catch (e) {
    console.error('Failed load cart', e);
    return {};
  }
};

export const checkAuth = async () => {
  const token = await AsyncStorage.getItem('token');

  if (!token) {
    router.replace('/auth/Login');
    return;
  }

  try {
    const res = await fetch(`${APP_CONFIG.API_URL}/api/pelanggan/me`, {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
      },
    });

    // token expired / unauthorized
    if (res.status === 401) {
      await AsyncStorage.multiRemove(['token', 'pelanggan_id', 'username',]);
      router.replace('/auth/Login');
      return;
    }

    const json = await res.json();

    if (!json.status) {
      router.replace('/auth/Login');
    }
  } catch (e) {
    Alert.alert('Error', 'Gagal load');
    router.replace('/auth/Login');
  }
};
