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
      id: json['id']?.toString() ?? '',
      title: json['title']?.toString() ?? '',
      handle: json['handle']?.toString() ?? '',
      body: json['body']?.toString() ?? '',
      bodySummary:
          (json['bodySummary'] ?? json['body_summary'])?.toString() ?? '',
      seo: SEO.fromJson(
        json['seo'] is Map<String, dynamic>
            ? json['seo'] as Map<String, dynamic>
            : <String, dynamic>{},
      ),
      createdAt: (json['createdAt'] ?? json['created_at'])?.toString() ?? '',
      updatedAt: (json['updatedAt'] ?? json['updated_at'])?.toString() ?? '',
    );
  }
}
