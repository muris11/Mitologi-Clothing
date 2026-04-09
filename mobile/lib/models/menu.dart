class MenuItem {
  final String title;
  final String path;

  MenuItem({required this.title, required this.path});

  factory MenuItem.fromJson(Map<String, dynamic> json) {
    return MenuItem(
      title: json['title']?.toString() ?? '',
      path: json['path']?.toString() ?? '',
    );
  }

  Map<String, dynamic> toJson() {
    return {'title': title, 'path': path};
  }
}
