import '../models/address.dart';
import 'api_service.dart';

class AddressService {
  final ApiService _api = ApiService();

  Future<List<Address>> getAddresses() async {
    final response = await _api.get('/profile/addresses', requiresAuth: true);
    if (response is List) {
      return response.map((data) => Address.fromJson(data)).toList();
    } else if (response is Map<String, dynamic> &&
        response['addresses'] is List) {
      return (response['addresses'] as List)
          .map((data) => Address.fromJson(data))
          .toList();
    }
    return [];
  }

  Future<Address> addAddress(Map<String, dynamic> data) async {
    final response = await _api.post(
      '/profile/addresses',
      body: data,
      requiresAuth: true,
    );
    return Address.fromJson(response['address']);
  }

  Future<Address> updateAddress(int id, Map<String, dynamic> data) async {
    final response = await _api.put(
      '/profile/addresses/$id',
      body: data,
      requiresAuth: true,
    );
    return Address.fromJson(response['address']);
  }

  Future<void> deleteAddress(int id) async {
    await _api.delete('/profile/addresses/$id', requiresAuth: true);
  }
}
