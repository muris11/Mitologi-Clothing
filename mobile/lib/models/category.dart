class Category {
  final int id;
  final String name;
  final String slug;
  final String handle;
  final String? description;
  final String? image;
  final int? productsCount;

  Category({
    required this.id,
    required this.name,
    required this.slug,
    required this.handle,
    this.description,
    this.image,
    this.productsCount,
  });

  factory Category.fromJson(Map<String, dynamic> json) {
    int parseInt(dynamic value, int fallback) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    return Category(
      id: parseInt(json['id'], 0),
      name: json['name']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      handle: json['handle']?.toString() ?? '',
      description: json['description']?.toString(),
      image: json['image']?.toString(),
      productsCount: json['products_count'] == null
          ? null
          : int.tryParse(json['products_count'].toString()),
    );
  }
}
