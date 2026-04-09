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
    int parseInt(dynamic value, [int fallback = 0]) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    return Material(
      id: parseInt(json['id']),
      name: json['name']?.toString() ?? '',
      description: json['description']?.toString(),
      icon: json['icon']?.toString(),
      sortOrder: (json['sort_order'] ?? json['sortOrder']) == null
          ? null
          : parseInt(json['sort_order'] ?? json['sortOrder']),
      createdAt: (json['created_at'] ?? json['createdAt']) != null
          ? DateTime.tryParse(
              (json['created_at'] ?? json['createdAt']).toString(),
            )
          : null,
      updatedAt: (json['updated_at'] ?? json['updatedAt']) != null
          ? DateTime.tryParse(
              (json['updated_at'] ?? json['updatedAt']).toString(),
            )
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
