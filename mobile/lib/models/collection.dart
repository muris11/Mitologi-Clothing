import 'seo.dart';

class Collection {
  final String handle;
  final String title;
  final String description;
  final SEO seo;
  final String path;
  final String updatedAt;

  Collection({
    required this.handle,
    required this.title,
    required this.description,
    required this.seo,
    required this.path,
    required this.updatedAt,
  });

  factory Collection.fromJson(Map<String, dynamic> json) {
    return Collection(
      handle: json['handle'] ?? '',
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      seo: SEO.fromJson(json['seo'] ?? {}),
      path: json['path'] ?? '',
      updatedAt: json['updatedAt'] ?? '',
    );
  }
}
