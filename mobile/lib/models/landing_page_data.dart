import 'product.dart';

class LandingPageData {
  final List<HeroSlide> heroSlides;
  final List<Feature> features;
  final List<Testimonial> testimonials;
  final List<MaterialInfo> materials;
  final List<Product>?
  featuredProducts; // Normally fetched separately or included

  LandingPageData({
    required this.heroSlides,
    required this.features,
    required this.testimonials,
    required this.materials,
    this.featuredProducts,
  });

  factory LandingPageData.fromJson(Map<String, dynamic> json) {
    final heroSlides = (json['hero_slides'] ?? json['heroSlides']) as List?;
    final features = (json['features'] as List?);
    final testimonials = (json['testimonials'] as List?);
    final materials = (json['materials'] as List?);

    return LandingPageData(
      heroSlides:
          heroSlides
              ?.map(
                (x) => HeroSlide.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      features:
          features
              ?.map(
                (x) => Feature.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      testimonials:
          testimonials
              ?.map(
                (x) =>
                    Testimonial.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      materials:
          materials
              ?.map(
                (x) =>
                    MaterialInfo.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
    );
  }
}

class HeroSlide {
  final String title;
  final String subtitle;
  final String image;
  final String linkText;
  final String target;

  HeroSlide({
    required this.title,
    required this.subtitle,
    required this.image,
    required this.linkText,
    required this.target,
  });

  factory HeroSlide.fromJson(Map<String, dynamic> json) => HeroSlide(
    title: json['title']?.toString() ?? '',
    subtitle: json['subtitle']?.toString() ?? '',
    image: (json['image'] ?? json['image_url'])?.toString() ?? '',
    linkText:
        (json['link_text'] ?? json['linkText'] ?? json['cta_text'])
            ?.toString() ??
        '',
    target:
        (json['target'] ?? json['link_url'] ?? json['cta_link'])?.toString() ??
        '',
  );
}

class Feature {
  final String title;
  final String description;
  final String icon;

  Feature({required this.title, required this.description, required this.icon});

  factory Feature.fromJson(Map<String, dynamic> json) => Feature(
    title: json['title']?.toString() ?? '',
    description: json['description']?.toString() ?? '',
    icon: json['icon']?.toString() ?? '',
  );
}

class Testimonial {
  final String name;
  final String role;
  final String content;
  final double rating;

  Testimonial({
    required this.name,
    required this.role,
    required this.content,
    required this.rating,
  });

  factory Testimonial.fromJson(Map<String, dynamic> json) => Testimonial(
    name: json['name']?.toString() ?? '',
    role: json['role']?.toString() ?? '',
    content: json['content']?.toString() ?? '',
    rating: (json['rating'] is num)
        ? (json['rating'] as num).toDouble()
        : double.tryParse(json['rating']?.toString() ?? '') ?? 5,
  );
}

class MaterialInfo {
  final String name;
  final String description;
  final String? image;

  MaterialInfo({required this.name, required this.description, this.image});

  factory MaterialInfo.fromJson(Map<String, dynamic> json) => MaterialInfo(
    name: json['name']?.toString() ?? '',
    description: json['description']?.toString() ?? '',
    image: json['image']?.toString(),
  );
}
