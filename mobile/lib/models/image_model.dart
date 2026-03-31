class ImageModel {
  final String url;
  final String altText;
  final int width;
  final int height;

  ImageModel({
    required this.url,
    required this.altText,
    required this.width,
    required this.height,
  });

  factory ImageModel.fromJson(Map<String, dynamic> json) {
    return ImageModel(
      url: json['url'] ?? '',
      altText: json['altText'] ?? '',
      width: json['width'] ?? 0,
      height: json['height'] ?? 0,
    );
  }

  Map<String, dynamic> toJson() {
    return {'url': url, 'altText': altText, 'width': width, 'height': height};
  }
}
