import 'category.dart';
import 'product.dart';
import 'material.dart';
import 'order_step.dart';
import 'tentang_kami/facility_model.dart';
import 'tentang_kami/team_member_model.dart';

class LandingPageData {
  final List<HeroSlide> heroSlides;
  final List<Feature> features;
  final List<Testimonial> testimonials;
  final List<MaterialInfo> materials;
  final List<Category> categories;
  final List<Product> newArrivals;
  final List<Product> bestSellers;
  final List<PortfolioItem> portfolioItems;
  final List<OrderStep> orderSteps;
  final List<Partner> partners;
  final List<PrintingMethod> printingMethods;
  final List<ProductPricing> productPricings;
  final List<Facility> facilities;
  final List<TeamMember> teamMembers;
  final SiteSettings? siteSettings;

  LandingPageData({
    required this.heroSlides,
    required this.features,
    required this.testimonials,
    required this.materials,
    required this.categories,
    required this.newArrivals,
    required this.bestSellers,
    required this.portfolioItems,
    required this.orderSteps,
    required this.partners,
    required this.printingMethods,
    required this.productPricings,
    required this.facilities,
    required this.teamMembers,
    this.siteSettings,
  });

  factory LandingPageData.fromJson(Map<String, dynamic> json) {
    final heroSlides = (json['heroSlides'] ?? json['hero_slides']) as List?;
    final features = (json['features'] as List?);
    final testimonials = (json['testimonials'] as List?);
    final materials = (json['materials'] as List?);
    final categories = (json['categories'] as List?);
    final newArrivals = (json['newArrivals'] ?? json['new_arrivals']) as List?;
    final bestSellers = (json['bestSellers'] ?? json['best_sellers']) as List?;
    final portfolioItems =
        (json['portfolioItems'] ?? json['portfolio_items']) as List?;
    final orderSteps = (json['orderSteps'] ?? json['order_steps']) as List?;
    final partners = (json['partners'] as List?);
    final printingMethods =
        (json['printingMethods'] ?? json['printing_methods']) as List?;
    final productPricings =
        (json['productPricings'] ?? json['product_pricings']) as List?;
    final facilities = (json['facilities'] as List?);
    final teamMembers =
        (json['teamMembers'] ?? json['team_members']) as List?;
    final siteSettingsJson = (json['siteSettings'] ?? json['site_settings']);

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
      categories:
          categories
              ?.map(
                (x) => Category.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      newArrivals:
          newArrivals
              ?.map(
                (x) => Product.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      bestSellers:
          bestSellers
              ?.map(
                (x) => Product.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      portfolioItems:
          portfolioItems
              ?.map(
                (x) =>
                    PortfolioItem.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      orderSteps:
          orderSteps
              ?.map(
                (x) => OrderStep.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      partners:
          partners
              ?.map(
                (x) => Partner.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      printingMethods:
          printingMethods
              ?.map(
                (x) => PrintingMethod.fromJson(
                  Map<String, dynamic>.from(x as Map),
                ),
              )
              .toList() ??
          [],
      productPricings:
          productPricings
              ?.map(
                (x) => ProductPricing.fromJson(
                  Map<String, dynamic>.from(x as Map),
                ),
              )
              .toList() ??
          [],
      facilities:
          facilities
              ?.map(
                (x) => Facility.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      teamMembers:
          teamMembers
              ?.map(
                (x) => TeamMember.fromJson(Map<String, dynamic>.from(x as Map)),
              )
              .toList() ??
          [],
      siteSettings:
          siteSettingsJson != null
              ? SiteSettings.fromJson(
                Map<String, dynamic>.from(siteSettingsJson as Map),
              )
              : null,
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
        (json['ctaText'] ?? json['linkText'] ?? json['cta_text'] ?? json['link_text'])
            ?.toString() ??
        '',
    target:
        (json['ctaLink'] ?? json['linkUrl'] ?? json['cta_link'] ?? json['link_url'] ?? json['target'])
            ?.toString() ??
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

class PortfolioItem {
  final String title;
  final String slug;
  final String description;
  final String? content;
  final String image;
  final List<String>? gallery;
  final String? category;
  final String? date;
  final String? link;
  final List<PortfolioItem>? relatedPortfolios;

  PortfolioItem({
    required this.title,
    required this.slug,
    required this.description,
    this.content,
    required this.image,
    this.gallery,
    this.category,
    this.date,
    this.link,
    this.relatedPortfolios,
  });

  factory PortfolioItem.fromJson(Map<String, dynamic> json) {
    final galleryJson = json['gallery'] as List?;
    final relatedJson = (json['relatedPortfolios'] ?? json['related_portfolios']) as List?;

    return PortfolioItem(
      title: json['title']?.toString() ?? '',
      slug: json['slug']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      content: json['content']?.toString(),
      image: (json['image'] ?? json['image_url'])?.toString() ?? '',
      gallery: galleryJson?.map((e) => e.toString()).toList(),
      category: json['category']?.toString(),
      date: json['date']?.toString(),
      link: json['link']?.toString(),
      relatedPortfolios: relatedJson
          ?.map((x) => PortfolioItem.fromJson(Map<String, dynamic>.from(x as Map)))
          .toList(),
    );
  }
}

class Partner {
  final String name;
  final String logo;
  final String? link;

  Partner({required this.name, required this.logo, this.link});

  factory Partner.fromJson(Map<String, dynamic> json) => Partner(
    name: json['name']?.toString() ?? '',
    logo: (json['logo'] ?? json['logo_url'])?.toString() ?? '',
    link: json['link']?.toString(),
  );
}

class PrintingMethod {
  final String name;
  final String description;
  final String? image;

  PrintingMethod({required this.name, required this.description, this.image});

  factory PrintingMethod.fromJson(Map<String, dynamic> json) => PrintingMethod(
    name: json['name']?.toString() ?? '',
    description: json['description']?.toString() ?? '',
    image: (json['image'] ?? json['image_url'])?.toString(),
  );
}

class ProductPricing {
  final String name;
  final double price;
  final String? unit;

  ProductPricing({required this.name, required this.price, this.unit});

  factory ProductPricing.fromJson(Map<String, dynamic> json) => ProductPricing(
    name: json['name']?.toString() ?? '',
    price:
        json['price'] is num
            ? (json['price'] as num).toDouble()
            : double.tryParse(json['price']?.toString() ?? '') ?? 0,
    unit: json['unit']?.toString(),
  );
}

class SiteSettings {
  final String? siteName;
  final String? siteLogo;
  final String? siteFavicon;
  final String? contactEmail;
  final String? contactPhone;
  final String? contactWha;
  final String? address;
  final String? instagramUrl;
  final String? facebookUrl;
  final String? twitterUrl;
  final String? youtubeUrl;
  final String? tiktokUrl;

  SiteSettings({
    this.siteName,
    this.siteLogo,
    this.siteFavicon,
    this.contactEmail,
    this.contactPhone,
    this.contactWha,
    this.address,
    this.instagramUrl,
    this.facebookUrl,
    this.twitterUrl,
    this.youtubeUrl,
    this.tiktokUrl,
  });

  factory SiteSettings.fromJson(Map<String, dynamic> json) => SiteSettings(
    siteName: (json['siteName'] ?? json['site_name'])?.toString(),
    siteLogo: (json['siteLogo'] ?? json['site_logo'])?.toString(),
    siteFavicon: (json['siteFavicon'] ?? json['site_favicon'])?.toString(),
    contactEmail: (json['contactEmail'] ?? json['contact_email'])?.toString(),
    contactPhone: (json['contactPhone'] ?? json['contact_phone'])?.toString(),
    contactWha: (json['contactWha'] ?? json['contact_wha'])?.toString(),
    address: json['address']?.toString(),
    instagramUrl: (json['instagramUrl'] ?? json['instagram_url'])?.toString(),
    facebookUrl: (json['facebookUrl'] ?? json['facebook_url'])?.toString(),
    twitterUrl: (json['twitterUrl'] ?? json['twitter_url'])?.toString(),
    youtubeUrl: (json['youtubeUrl'] ?? json['youtube_url'])?.toString(),
    tiktokUrl: (json['tiktokUrl'] ?? json['tiktok_url'])?.toString(),
  );
}
