import '../models/user.dart';
import 'api_service.dart';

class ProfileService {
  final ApiService _api = ApiService();

  /// Get current user profile
  Future<User> getProfile() async {
    final response = await _api.get('/profile', requiresAuth: true);
    return User.fromJson(response);
  }

  /// Update profile
  Future<User> updateProfile({
    String? name,
    String? email,
    String? phone,
    String? address,
    String? city,
    String? province,
    String? postalCode,
  }) async {
    final body = <String, dynamic>{};
    if (name != null) body['name'] = name;
    if (email != null) body['email'] = email;
    if (phone != null) body['phone'] = phone;
    if (address != null) body['address'] = address;
    if (city != null) body['city'] = city;
    if (province != null) body['province'] = province;
    if (postalCode != null) body['postal_code'] = postalCode;

    final response = await _api.put('/profile', body: body, requiresAuth: true);
    return User.fromJson(response);
  }

  /// Update avatar
  Future<String> updateAvatar(String imagePath) async {
    final response = await _api.multipartPost(
      '/profile/avatar',
      filePath: imagePath,
      fileField: 'avatar',
      requiresAuth: true,
    );

    // Assuming backend returns { avatar_url: "..." }
    return response['avatar_url'] ?? '';
  }

  /// Update password
  Future<bool> updatePassword({
    required String currentPassword,
    required String newPassword,
    required String confirmPassword,
  }) async {
    try {
      await _api.put(
        '/profile/password',
        body: {
          'current_password': currentPassword,
          'password': newPassword,
          'password_confirmation': confirmPassword,
        },
        requiresAuth: true,
      );
      return true;
    } catch (e) {
      return false;
    }
  }
}
