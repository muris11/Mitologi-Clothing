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
      handle: json['handle']?.toString() ?? '',
      availableForSale: json['availableForSale'] == true || json['available_for_sale'] == true,
      title: json['title']?.toString() ?? '',
      description: json['description']?.toString() ?? '',
      descriptionHtml: json['descriptionHtml']?.toString() ?? '',
      options:
          (json['options'] as List<dynamic>?)
              ?.map(
                (e) =>
                    ProductOption.fromJson(Map<String, dynamic>.from(e as Map)),
              )
              .toList() ??
          [],
      priceRange: PriceRange.fromJson(
        json['priceRange'] is Map<String, dynamic>
            ? json['priceRange'] as Map<String, dynamic>
            : <String, dynamic>{},
      ),
      variants:
          (json['variants'] as List<dynamic>?)
              ?.map(
                (e) => ProductVariant.fromJson(
                  Map<String, dynamic>.from(e as Map),
                ),
              )
              .toList() ??
          [],
      featuredImage: ImageModel.fromJson(
        json['featuredImage'] is Map<String, dynamic>
            ? json['featuredImage'] as Map<String, dynamic>
            : <String, dynamic>{},
      ),
      images:
          (json['images'] as List<dynamic>?)
              ?.map(
                (e) => ImageModel.fromJson(Map<String, dynamic>.from(e as Map)),
              )
              .toList() ??
          [],
      seo: SEO.fromJson(
        json['seo'] is Map<String, dynamic>
            ? json['seo'] as Map<String, dynamic>
            : <String, dynamic>{},
      ),
      tags:
          (json['tags'] as List<dynamic>?)?.map((e) => e.toString()).toList() ??
          [],
      totalStock: (json['totalStock'] ?? json['total_stock']) == null
          ? null
          : int.tryParse((json['totalStock'] ?? json['total_stock']).toString()),
      updatedAt: (json['updatedAt'] ?? json['updated_at'])?.toString() ?? '',
      isWishlisted: (json['isWishlisted'] ?? json['is_wishlisted']) as bool?,
      averageRating: (json['averageRating'] ?? json['average_rating']) == null
          ? null
          : double.tryParse((json['averageRating'] ?? json['average_rating']).toString()),
      totalReviews: (json['totalReviews'] ?? json['total_reviews'] ?? json['reviews_count']) == null
          ? null
          : int.tryParse((json['totalReviews'] ?? json['total_reviews'] ?? json['reviews_count']).toString()),
      totalSold: (json['totalSold'] ?? json['total_sold']) == null
          ? null
          : int.tryParse((json['totalSold'] ?? json['total_sold']).toString()),
    );
  }
}
