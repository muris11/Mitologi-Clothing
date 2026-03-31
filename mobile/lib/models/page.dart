import 'seo.dart';

class PageData {
  final String id;
  final String title;
  final String handle;
  final String body;
  final String bodySummary;
  final SEO seo;
  final String createdAt;
  final String updatedAt;

  PageData({
    required this.id,
    required this.title,
    required this.handle,
    required this.body,
    required this.bodySummary,
    required this.seo,
    required this.createdAt,
    required this.updatedAt,
  });

  factory PageData.fromJson(Map<String, dynamic> json) {
    return PageData(
      id: json['id'] ?? '',
      title: json['title'] ?? '',
      handle: json['handle'] ?? '',
      body: json['body'] ?? '',
      bodySummary: json['bodySummary'] ?? '',
      seo: SEO.fromJson(json['seo'] ?? {}),
      createdAt: json['createdAt'] ?? '',
      updatedAt: json['updatedAt'] ?? '',
    );
  }
}
