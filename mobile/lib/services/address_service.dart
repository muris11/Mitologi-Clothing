import '../models/address.dart';
import 'api_service.dart';

class AddressService {
  AddressService({ApiService? api}) : _api = api ?? ApiService();

  final ApiService _api;

  Future<List<Address>> getAddresses() async {
    final response = await _api.get('/profile/addresses', requiresAuth: true);
    if (response is List) {
      return response
          .map(
            (data) => Address.fromJson(Map<String, dynamic>.from(data as Map)),
          )
          .toList();
    } else if (response is Map<String, dynamic> && response['data'] is List) {
      return (response['data'] as List)
          .map(
            (data) => Address.fromJson(Map<String, dynamic>.from(data as Map)),
          )
          .toList();
    } else if (response is Map<String, dynamic> &&
        response['addresses'] is List) {
      return (response['addresses'] as List)
          .map(
            (data) => Address.fromJson(Map<String, dynamic>.from(data as Map)),
          )
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
    final payload = response is Map<String, dynamic>
        ? response['data']
        : response;
    return Address.fromJson(Map<String, dynamic>.from(payload as Map));
  }

  Future<Address> updateAddress(int id, Map<String, dynamic> data) async {
    final response = await _api.put(
      '/profile/addresses/$id',
      body: data,
      requiresAuth: true,
    );
    final payload = response is Map<String, dynamic>
        ? response['data']
        : response;
    return Address.fromJson(Map<String, dynamic>.from(payload as Map));
  }

  Future<void> deleteAddress(int id) async {
    await _api.delete('/profile/addresses/$id', requiresAuth: true);
  }
}
