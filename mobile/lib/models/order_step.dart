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
    return OrderStep(
      id: json['id'] ?? 0,
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      icon: json['icon'],
      sortOrder: json['sort_order'],
      type: json['type'],
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
