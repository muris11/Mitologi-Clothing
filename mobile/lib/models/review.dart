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
    int parseInt(dynamic value, int fallback) {
      if (value is int) return value;
      return int.tryParse(value?.toString() ?? '') ?? fallback;
    }

    String parseString(dynamic value, [String fallback = '']) {
      if (value is String) return value;
      return value?.toString() ?? fallback;
    }

    return ReviewItem(
      id: parseInt(json['id'], 0),
      productId: parseInt(json['product_id'] ?? json['productId'], 0),
      userId: parseInt(json['user_id'] ?? json['userId'], 0),
      userName: parseString(
        json['user_name'] ?? json['userName'] ?? json['user']?['name'],
      ),
      userAvatar:
          (json['user_avatar'] ??
                  json['userAvatar'] ??
                  json['user']?['avatar_url'] ??
                  json['user']?['avatar'])
              ?.toString(),
      rating: parseInt(json['rating'], 5),
      comment: parseString(json['comment']),
      adminReply: (json['admin_reply'] ?? json['adminReply'])?.toString(),
      adminRepliedAt: (json['admin_replied_at'] ?? json['adminRepliedAt'])
          ?.toString(),
      createdAt: parseString(json['created_at'] ?? json['createdAt']),
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
    final Map<String, int> breakdown = {};
    final rawBreakdown = json['rating_breakdown'] ?? json['ratingBreakdown'];
    if (rawBreakdown is Map) {
      rawBreakdown.forEach((key, value) {
        breakdown[key.toString()] = int.tryParse(value.toString()) ?? 0;
      });
    }

    return ReviewSummary(
      averageRating: (json['average_rating'] ?? json['averageRating']) == null
          ? 0.0
          : double.tryParse(
                  (json['average_rating'] ?? json['averageRating']).toString(),
                ) ??
                0.0,
      totalReviews: (json['total_reviews'] ?? json['totalReviews']) is int
          ? (json['total_reviews'] ?? json['totalReviews']) as int
          : int.tryParse(
                  (json['total_reviews'] ?? json['totalReviews'])?.toString() ??
                      '0',
                ) ??
                0,
      ratingBreakdown: breakdown,
    );
  }
}
