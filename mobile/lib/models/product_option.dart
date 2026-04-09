class ProductOption {
  final String id;
  final String name;
  final List<String> values;

  ProductOption({required this.id, required this.name, required this.values});

  factory ProductOption.fromJson(Map<String, dynamic> json) {
    return ProductOption(
      id: json['id']?.toString() ?? '',
      name: json['name']?.toString() ?? '',
      values:
          (json['values'] as List<dynamic>?)
              ?.map((e) => e.toString())
              .toList() ??
          [],
    );
  }

  Map<String, dynamic> toJson() {
    return {'id': id, 'name': name, 'values': values};
  }
}
