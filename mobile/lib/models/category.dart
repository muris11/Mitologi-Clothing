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
    return Category(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      name: json['name'] ?? '',
      slug: json['slug'] ?? '',
      handle: json['handle'] ?? '',
      description: json['description'],
      image: json['image'],
      productsCount: json['products_count'] == null
          ? null
          : int.tryParse(json['products_count'].toString()),
    );
  }
}
