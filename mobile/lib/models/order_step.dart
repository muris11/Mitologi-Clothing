class OrderStep {
  final int id;
  final String title;
  final String description;
  final String? icon;
  final int? sortOrder;
  final String? type; // 'langsung' | 'ecommerce'
  final DateTime? createdAt;
  final DateTime? updatedAt;

  OrderStep({
    required this.id,
    required this.title,
    required this.description,
    this.icon,
    this.sortOrder,
    this.type,
    this.createdAt,
    this.updatedAt,
  });

  factory OrderStep.fromJson(Map<String, dynamic> json) {
    int parseInt(dynamic value, [int fallback = 0]) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    return OrderStep(
      id: parseInt(json['id']),
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      icon: json['icon']?.toString(),
      sortOrder: (json['sort_order'] ?? json['sortOrder']) == null
          ? null
          : parseInt(json['sort_order'] ?? json['sortOrder']),
      type: json['type']?.toString(),
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
      'title': title,
      'description': description,
      'icon': icon,
      'sort_order': sortOrder,
      'type': type,
      'created_at': createdAt?.toIso8601String(),
      'updated_at': updatedAt?.toIso8601String(),
    };
  }
}
