import 'product_option.dart';
import 'product_variant.dart';
import 'price_range.dart';
import 'image_model.dart';
import 'seo.dart';

class Product {
  final String id;
  final String handle;
  final bool availableForSale;
  final String title;
  final String description;
  final String descriptionHtml;
  final List<ProductOption> options;
  final PriceRange priceRange;
  final List<ProductVariant> variants;
  final ImageModel featuredImage;
  final List<ImageModel> images;
  final SEO seo;
  final List<String> tags;
  final int? totalStock;
  final String updatedAt;
  final bool? isWishlisted;
  final double? averageRating;
  final int? totalReviews;
  final int? totalSold;

  Product({
    required this.id,
    required this.handle,
    required this.availableForSale,
    required this.title,
    required this.description,
    required this.descriptionHtml,
    required this.options,
    required this.priceRange,
    required this.variants,
    required this.featuredImage,
    required this.images,
    required this.seo,
    required this.tags,
    this.totalStock,
    required this.updatedAt,
    this.isWishlisted,
    this.averageRating,
    this.totalReviews,
    this.totalSold,
  });

  factory Product.fromJson(Map<String, dynamic> json) {
    return Product(
      id: json['id']?.toString() ?? '',
      handle: json['handle'] ?? '',
      availableForSale: json['availableForSale'] ?? false,
      title: json['title'] ?? '',
      description: json['description'] ?? '',
      descriptionHtml: json['descriptionHtml'] ?? '',
      options:
          (json['options'] as List<dynamic>?)
              ?.map((e) => ProductOption.fromJson(e))
              .toList() ??
          [],
      priceRange: PriceRange.fromJson(json['priceRange'] ?? {}),
      variants:
          (json['variants'] as List<dynamic>?)
              ?.map((e) => ProductVariant.fromJson(e))
              .toList() ??
          [],
      featuredImage: ImageModel.fromJson(json['featuredImage'] ?? {}),
      images:
          (json['images'] as List<dynamic>?)
              ?.map((e) => ImageModel.fromJson(e))
              .toList() ??
          [],
      seo: SEO.fromJson(json['seo'] ?? {}),
      tags:
          (json['tags'] as List<dynamic>?)?.map((e) => e.toString()).toList() ??
          [],
      totalStock: json['totalStock'] == null
          ? null
          : int.tryParse(json['totalStock'].toString()),
      updatedAt: json['updatedAt'] ?? '',
      isWishlisted: json['isWishlisted'],
      averageRating: json['averageRating'] == null
          ? null
          : double.tryParse(json['averageRating'].toString()),
      totalReviews: json['totalReviews'] == null
          ? null
          : int.tryParse(json['totalReviews'].toString()),
      totalSold: json['totalSold'] == null
          ? null
          : int.tryParse(json['totalSold'].toString()),
    );
  }
}
