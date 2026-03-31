class Address {
  final int id;
  final String label;
  final String recipientName;
  final String phone;
  final String addressLine1;
  final String? addressLine2;
  final String city;
  final String province;
  final String postalCode;
  final String country;
  final bool isPrimary;

  Address({
    required this.id,
    required this.label,
    required this.recipientName,
    required this.phone,
    required this.addressLine1,
    this.addressLine2,
    required this.city,
    required this.province,
    required this.postalCode,
    required this.country,
    required this.isPrimary,
  });

  factory Address.fromJson(Map<String, dynamic> json) {
    return Address(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      label: json['label'] ?? '',
      recipientName: json['recipient_name'] ?? json['recipientName'] ?? '',
      phone: json['phone'] ?? '',
      addressLine1: json['address_line_1'] ?? json['addressLine1'] ?? '',
      addressLine2: json['address_line_2'] ?? json['addressLine2'],
      city: json['city'] ?? '',
      province: json['province'] ?? '',
      postalCode: json['postal_code'] ?? json['postalCode'] ?? '',
      country: json['country'] ?? 'Indonesia',
      isPrimary: json['is_primary'] == 1 || json['isPrimary'] == true,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'label': label,
      'recipient_name': recipientName,
      'phone': phone,
      'address_line_1': addressLine1,
      'address_line_2': addressLine2,
      'city': city,
      'province': province,
      'postal_code': postalCode,
      'country': country,
      'is_primary': isPrimary,
    };
  }
}
