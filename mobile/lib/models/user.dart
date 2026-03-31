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
    return User(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      name: json['name'] ?? '',
      email: json['email'] ?? '',
      role: json['role'] ?? json['role_name'],
      avatarUrl: json['avatar_url'] ?? json['avatar'],
      phone: json['phone'],
      addresses: (json['addresses'] as List<dynamic>?)
          ?.map((e) => Address.fromJson(e))
          .toList(),
      address: json['address'],
      city: json['city'],
      province: json['province'],
      postalCode: json['postal_code'] ?? json['postalCode'],
    );
  }
}
