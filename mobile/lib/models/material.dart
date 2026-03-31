class Material {
  final int id;
  final String name;
  final String? description;
  final String? icon;
  final int? sortOrder;
  final DateTime? createdAt;
  final DateTime? updatedAt;

  Material({
    required this.id,
    required this.name,
    this.description,
    this.icon,
    this.sortOrder,
    this.createdAt,
    this.updatedAt,
  });

  factory Material.fromJson(Map<String, dynamic> json) {
    return Material(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      description: json['description'],
      icon: json['icon'],
      sortOrder: json['sort_order'],
      createdAt: json['created_at'] != null
          ? DateTime.tryParse(json['created_at'])
          : null,
      updatedAt: json['updated_at'] != null
          ? DateTime.tryParse(json['updated_at'])
          : null,
    );
  }

  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'name': name,
      'description': description,
      'icon': icon,
      'sort_order': sortOrder,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }
}
