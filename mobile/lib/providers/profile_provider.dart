import 'package:flutter/foundation.dart';
import '../models/user.dart';
import '../services/profile_service.dart';

class ProfileProvider with ChangeNotifier {
  final ProfileService _profileService = ProfileService();

  User? _user;
  bool _isLoading = false;
  String? _error;

  User? get user => _user;
  bool get isLoading => _isLoading;
  String? get error => _error;

  void _setLoading(bool value) {
    _isLoading = value;
    notifyListeners();
  }

  void _setError(String? message) {
    _error = message;
    notifyListeners();
  }

  Future<void> fetchProfile() async {
    _setLoading(true);
    _setError(null);
    try {
      _user = await _profileService.getProfile();
    } catch (e) {
      _setError(e.toString());
    } finally {
      _setLoading(false);
    }
  }

  Future<bool> updateProfile({
    String? name,
    String? email,
    String? phone,
    String? address,
    String? city,
    String? province,
    String? postalCode,
  }) async {
    _setLoading(true);
    _setError(null);
    try {
      _user = await _profileService.updateProfile(
        name: name,
        email: email,
        phone: phone,
        address: address,
        city: city,
        province: province,
        postalCode: postalCode,
      );
      _setLoading(false);
      return true;
    } catch (e) {
      _setError(e.toString());
      _setLoading(false);
      return false;
    }
  }

  Future<bool> updateAvatar(String imagePath) async {
    _setLoading(true);
    _setError(null);
    try {
      await _profileService.updateAvatar(imagePath);
      // If we already have a user, just update the avatar locally
      if (_user != null) {
        // Technically User model has an avatar string or similar.
        // If your User model doesn't have it, we might just re-fetch the profile.
        await fetchProfile();
      }
      _setLoading(false);
      return true;
    } catch (e) {
      _setError(e.toString());
      _setLoading(false);
      return false;
    }
  }

  Future<bool> changePassword({
    required String currentPassword,
    required String newPassword,
    required String confirmPassword,
  }) async {
    _setLoading(true);
    _setError(null);
    try {
      final success = await _profileService.updatePassword(
        currentPassword: currentPassword,
        newPassword: newPassword,
        confirmPassword: confirmPassword,
      );
      if (!success) {
        _setError('Gagal mengubah password. Periksa password lama Anda.');
      }
      _setLoading(false);
      return success;
    } catch (e) {
      _setError(e.toString());
      _setLoading(false);
      return false;
    }
  }

  void clearProfile() {
    _user = null;
    _error = null;
    notifyListeners();
  }
}
