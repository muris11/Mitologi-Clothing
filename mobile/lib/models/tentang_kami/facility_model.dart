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
    return Facility(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'],
      image: json['image'],
      sortOrder: json['sort_order'] as int?,
    );
  }
}
