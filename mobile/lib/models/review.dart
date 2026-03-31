class ReviewItem {
  final int id;
  final int productId;
  final int userId;
  final String userName;
  final String? userAvatar;
  final int rating;
  final String comment;
  final String? adminReply;
  final String? adminRepliedAt;
  final String createdAt;

  ReviewItem({
    required this.id,
    required this.productId,
    required this.userId,
    required this.userName,
    this.userAvatar,
    required this.rating,
    required this.comment,
    this.adminReply,
    this.adminRepliedAt,
    required this.createdAt,
  });

  factory ReviewItem.fromJson(Map<String, dynamic> json) {
    return ReviewItem(
      id: json['id'] is int
          ? json['id']
          : int.tryParse(json['id']?.toString() ?? '0') ?? 0,
      productId: json['product_id'] is int
          ? json['product_id']
          : int.tryParse(json['product_id']?.toString() ?? '0') ?? 0,
      userId: json['user_id'] is int
          ? json['user_id']
          : int.tryParse(json['user_id']?.toString() ?? '0') ?? 0,
      userName: json['user_name'] ?? json['user']?['name'] ?? '',
      userAvatar:
          json['user_avatar'] ??
          json['user']?['avatar_url'] ??
          json['user']?['avatar'],
      rating: json['rating'] is int
          ? json['rating']
          : int.tryParse(json['rating']?.toString() ?? '5') ?? 5,
      comment: json['comment'] ?? '',
      adminReply: json['admin_reply'],
      adminRepliedAt: json['admin_replied_at'],
      createdAt: json['created_at'] ?? '',
    );
  }
}

class ReviewSummary {
  final double averageRating;
  final int totalReviews;
  final Map<String, int> ratingBreakdown;

  ReviewSummary({
    required this.averageRating,
    required this.totalReviews,
    required this.ratingBreakdown,
  });

  factory ReviewSummary.fromJson(Map<String, dynamic> json) {
    Map<String, int> breakdown = {};
    if (json['rating_breakdown'] != null) {
      json['rating_breakdown'].forEach((key, value) {
        breakdown[key.toString()] = int.tryParse(value.toString()) ?? 0;
      });
    }

    return ReviewSummary(
      averageRating: json['average_rating'] == null
          ? 0.0
          : double.tryParse(json['average_rating'].toString()) ?? 0.0,
      totalReviews: json['total_reviews'] is int
          ? json['total_reviews']
          : int.tryParse(json['total_reviews']?.toString() ?? '0') ?? 0,
      ratingBreakdown: breakdown,
    );
  }
}
