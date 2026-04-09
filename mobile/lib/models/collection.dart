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
      handle: json['handle']?.toString() ?? '',
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      seo: SEO.fromJson(
        json['seo'] is Map<String, dynamic>
            ? json['seo'] as Map<String, dynamic>
            : <String, dynamic>{},
      ),
      path: json['path']?.toString() ?? '',
      updatedAt: json['updatedAt']?.toString() ?? '',
    );
  }
}
