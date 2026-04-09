import 'address.dart';

class User {
  final int id;
  final String name;
  final String email;
  final String? role;
  final String? avatarUrl;
  final String? phone;
  final List<Address>? addresses;
  final String? address;
  final String? city;
  final String? province;
  final String? postalCode;

  User({
    required this.id,
    required this.name,
    required this.email,
    this.role,
    this.avatarUrl,
    this.phone,
    this.addresses,
    this.address,
    this.city,
    this.province,
    this.postalCode,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    int parseInt(dynamic value, int fallback) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    String parseString(dynamic value, [String fallback = '']) {
      if (value is String) return value;
      return value?.toString() ?? fallback;
    }

    return User(
      id: parseInt(json['id'], 0),
      name: parseString(json['name']),
      email: parseString(json['email']),
      role: (json['role'] ?? json['role_name'])?.toString(),
      avatarUrl: (json['avatar_url'] ?? json['avatarUrl'] ?? json['avatar'])
          ?.toString(),
      phone: json['phone']?.toString(),
      addresses: (json['addresses'] as List<dynamic>?)
          ?.map((e) => Address.fromJson(Map<String, dynamic>.from(e as Map)))
          .toList(),
      address: json['address']?.toString(),
      city: json['city']?.toString(),
      province: json['province']?.toString(),
      postalCode: (json['postal_code'] ?? json['postalCode'])?.toString(),
    );
  }
}
