import 'dart:ui';
import 'package:flutter/material.dart';

class TextBlurReveal extends StatefulWidget {
  final String text;
  final TextStyle? style;
  final Duration characterDelay;
  final Duration duration;
  final TextAlign textAlign;

  const TextBlurReveal({
    super.key,
    required this.text,
    this.style,
    this.characterDelay = const Duration(milliseconds: 30),
    this.duration = const Duration(milliseconds: 400),
    this.textAlign = TextAlign.center,
  });

  @override
  State<TextBlurReveal> createState() => _TextBlurRevealState();
}

class _TextBlurRevealState extends State<TextBlurReveal>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;

  @override
  void initState() {
    super.initState();
    final totalDuration =
        widget.characterDelay * widget.text.length + widget.duration;
    _controller = AnimationController(vsync: this, duration: totalDuration)
      ..forward();
  }

  @override
  void dispose() {
    _controller.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return AnimatedBuilder(
      animation: _controller,
      builder: (context, child) {
        return Wrap(
          alignment: widget.textAlign == TextAlign.center
              ? WrapAlignment.center
              : WrapAlignment.start,
          children: List.generate(widget.text.length, (index) {
            // Create per-character animation
            final charStartTime =
                (index * widget.characterDelay.inMilliseconds) /
                _controller.duration!.inMilliseconds;
            final charEndTime =
                charStartTime +
                (widget.duration.inMilliseconds /
                    _controller.duration!.inMilliseconds);

            final charAnimation = Tween<double>(begin: 0.0, end: 1.0).animate(
              CurvedAnimation(
                parent: _controller,
                curve: Interval(
                  charStartTime.clamp(0.0, 1.0),
                  charEndTime.clamp(0.0, 1.0),
                  curve: Curves.easeOutCubic,
                ),
              ),
            );

            final blurAmount = (1.0 - charAnimation.value) * 8;
            final opacity = charAnimation.value;

            return Opacity(
              opacity: opacity,
              child: ImageFiltered(
                imageFilter: ImageFilter.blur(
                  sigmaX: blurAmount,
                  sigmaY: blurAmount,
                ),
                child: Text(widget.text[index], style: widget.style),
              ),
            );
          }),
        );
      },
    );
  }
}
