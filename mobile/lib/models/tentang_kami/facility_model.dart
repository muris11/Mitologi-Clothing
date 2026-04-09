class Facility {
  final int id;
  final String name;
  final String? description;
  final String? image;
  final int? sortOrder;

  Facility({
    required this.id,
    required this.name,
    this.description,
    this.image,
    this.sortOrder,
  });

  factory Facility.fromJson(Map<String, dynamic> json) {
    int parseInt(dynamic value, int fallback) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    return Facility(
      id: parseInt(json['id'], 0),
      name: json['name']?.toString() ?? '',
      description: json['description']?.toString(),
      image: json['image']?.toString(),
      sortOrder: (json['sort_order'] ?? json['sortOrder']) == null
          ? null
          : parseInt(json['sort_order'] ?? json['sortOrder'], 0),
    );
  }
}
