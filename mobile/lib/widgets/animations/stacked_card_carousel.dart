import 'dart:math';
import 'package:flutter/material.dart';

class StackedCardCarousel extends StatefulWidget {
  final List<Widget> items;
  final double height;

  const StackedCardCarousel({
    super.key,
    required this.items,
    this.height = 360,
  });

  @override
  State<StackedCardCarousel> createState() => _StackedCardCarouselState();
}

class _StackedCardCarouselState extends State<StackedCardCarousel> {
  late PageController _pageController;
  double _currentPage = 0.0;

  @override
  void initState() {
    super.initState();
    _pageController = PageController(viewportFraction: 0.75)
      ..addListener(() {
        setState(() {
          _currentPage = _pageController.page ?? 0.0;
        });
      });
  }

  @override
  void dispose() {
    _pageController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    if (widget.items.isEmpty) return const SizedBox.shrink();

    return SizedBox(
      height: widget.height,
      child: PageView.builder(
        controller: _pageController,
        clipBehavior: Clip.hardEdge,
        itemCount: widget.items.length,
        itemBuilder: (context, index) {
          // Calculate difference between current page and this item's index
          final double difference = index - _currentPage;

          // Scale down items that are further away from the center
          final double scale = 1 - (difference.abs() * 0.15).clamp(0.0, 0.3);

          // Translate items to create overlapping stacked effect
          final double offset = difference * 40;

          // Calculate rotation
          final double rotate = difference * pi / 16;

          // Fade out items that are far away
          final double opacity = 1 - (difference.abs() * 0.4).clamp(0.0, 0.8);

          return Transform(
            transform: Matrix4.identity()
              ..setEntry(3, 2, 0.001) // perspective
              // ignore: deprecated_member_use
              ..translate(offset, difference.abs() * 20)
              // ignore: deprecated_member_use
              ..scale(scale)
              ..rotateZ(rotate.clamp(-0.2, 0.2)),
            alignment: Alignment.center,
            child: Opacity(
              opacity: opacity,
              child: Padding(
                padding: const EdgeInsets.symmetric(
                  horizontal: 8.0,
                  vertical: 16.0,
                ),
                child: widget.items[index],
              ),
            ),
          );
        },
      ),
    );
  }
}
