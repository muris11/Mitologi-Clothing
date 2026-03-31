import 'package:flutter/material.dart';
import '../../models/review.dart';
import '../../utils/storage_url.dart';
import '../../config/theme.dart';

class ReviewCard extends StatelessWidget {
  final ReviewItem review;

  const ReviewCard({super.key, required this.review});

  @override
  Widget build(BuildContext context) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 16),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              CircleAvatar(
                radius: 16,
                backgroundColor: AppTheme.surfaceContainerLow,
                backgroundImage: review.userAvatar != null
                    ? NetworkImage(StorageUrl.format(review.userAvatar!))
                    : null,
                child: review.userAvatar == null
                    ? const Icon(
                        Icons.person,
                        color: AppTheme.onSurfaceVariant,
                        size: 20,
                      )
                    : null,
              ),
              const SizedBox(width: 12),
              Text(
                review.userName,
                style: const TextStyle(
                  fontWeight: FontWeight.w600,
                  fontSize: 13,
                  color: AppTheme.onSurface,
                ),
              ),
              const Spacer(),
              Row(
                children: List.generate(5, (index) {
                  return Icon(
                    index < review.rating ? Icons.star : Icons.star_border,
                    color: index < review.rating
                        ? AppTheme.secondary
                        : AppTheme.surfaceContainerHigh,
                    size: 16,
                  );
                }),
              ),
            ],
          ),
          const SizedBox(height: 8),
          Text(
            review.comment,
            style: const TextStyle(
              color: AppTheme.onSurfaceVariant,
              fontSize: 13,
              height: 1.4,
            ),
          ),
          if (review.adminReply != null)
            Container(
              margin: const EdgeInsets.only(top: 12),
              padding: const EdgeInsets.all(12),
              decoration: BoxDecoration(
                color: AppTheme.surfaceContainerLow,
                borderRadius: BorderRadius.circular(12),
              ),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Row(
                    children: [
                      Icon(Icons.storefront, size: 16, color: AppTheme.primary),
                      SizedBox(width: 6),
                      Text(
                        'Balasan Penjual',
                        style: TextStyle(
                          fontWeight: FontWeight.w600,
                          fontSize: 12,
                          color: AppTheme.primary,
                        ),
                      ),
                    ],
                  ),
                  const SizedBox(height: 6),
                  Text(
                    review.adminReply!,
                    style: const TextStyle(
                      color: AppTheme.onSurfaceVariant,
                      fontSize: 13,
                      height: 1.4,
                    ),
                  ),
                ],
              ),
            ),
        ],
      ),
    );
  }
}
