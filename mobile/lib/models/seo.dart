class SEO {
  final String title;
  final String description;

  SEO({required this.title, required this.description});

  factory SEO.fromJson(Map<String, dynamic> json) {
    return SEO(
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {'title': title, 'description': description};
  }
}
