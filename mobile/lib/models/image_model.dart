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
    int parseInt(dynamic value, int fallback) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    return ImageModel(
      url: json['url']?.toString() ?? '',
      altText: json['altText']?.toString() ?? '',
      width: parseInt(json['width'], 0),
      height: parseInt(json['height'], 0),
    );
  }

  Map<String, dynamic> toJson() {
    return {'url': url, 'altText': altText, 'width': width, 'height': height};
  }
}
