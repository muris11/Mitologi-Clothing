import 'package:flutter/material.dart';
import 'dart:async';
import '../../config/theme.dart';

class TypingAnimation extends StatefulWidget {
  final String text;
  final Duration duration;
  final TextStyle? style;
  final bool animate;

  const TypingAnimation({
    super.key,
    required this.text,
    this.duration = const Duration(milliseconds: 100),
    this.style,
    this.animate = false,
  });

  @override
  State<TypingAnimation> createState() => _TypingAnimationState();
}

class _TypingAnimationState extends State<TypingAnimation> {
  String _displayedText = '';
  int _charIndex = 0;
  Timer? _timer;

  @override
  void didUpdateWidget(TypingAnimation oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (widget.animate && !oldWidget.animate) {
      _startAnimation();
    } else if (!widget.animate && oldWidget.animate) {
      _stopAnimation();
    }
  }

  void _startAnimation() {
    _charIndex = 0;
    _displayedText = '';
    _timer = Timer.periodic(widget.duration, (timer) {
      if (_charIndex < widget.text.length) {
        if (mounted) {
          setState(() {
            _displayedText = widget.text.substring(0, _charIndex + 1);
            _charIndex++;
          });
        }
      } else {
        _stopAnimation();
      }
    });
  }

  void _stopAnimation() {
    _timer?.cancel();
    _timer = null;
  }

  @override
  void dispose() {
    _stopAnimation();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Text(
      widget.animate ? _displayedText : widget.text,
      style:
          widget.style ??
          const TextStyle(fontSize: 32, fontWeight: FontWeight.bold),
    );
  }
}

/// Bouncing dots indicator for chatbot typing state
/// Shows 3 dots that bounce in sequence (●●●)
class BouncingDotsIndicator extends StatefulWidget {
  final Color color;
  final double dotSize;
  final Duration animationDuration;

  const BouncingDotsIndicator({
    super.key,
    this.color = AppTheme.primary,
    this.dotSize = 8.0,
    this.animationDuration = const Duration(milliseconds: 1200),
  });

  @override
  State<BouncingDotsIndicator> createState() => _BouncingDotsIndicatorState();
}

class _BouncingDotsIndicatorState extends State<BouncingDotsIndicator>
    with TickerProviderStateMixin {
  late List<AnimationController> _controllers;
  late List<Animation<double>> _animations;

  @override
  void initState() {
    super.initState();
    _controllers = List.generate(3, (index) {
      return AnimationController(
        vsync: this,
        duration: widget.animationDuration,
      );
    });

    _animations = _controllers.map((controller) {
      return Tween<double>(
        begin: 0.0,
        end: -8.0,
      ).animate(CurvedAnimation(parent: controller, curve: Curves.easeInOut));
    }).toList();

    // Start animations with staggered delays
    for (int i = 0; i < _controllers.length; i++) {
      Future.delayed(Duration(milliseconds: i * 150), () {
        if (mounted) {
          _controllers[i].repeat(reverse: true);
        }
      });
    }
  }

  @override
  void dispose() {
    for (var controller in _controllers) {
      controller.dispose();
    }
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Row(
      mainAxisSize: MainAxisSize.min,
      children: List.generate(3, (index) {
        return AnimatedBuilder(
          animation: _animations[index],
          builder: (context, child) {
            return Transform.translate(
              offset: Offset(0, _animations[index].value),
              child: Container(
                width: widget.dotSize,
                height: widget.dotSize,
                margin: const EdgeInsets.symmetric(horizontal: 2),
                decoration: BoxDecoration(
                  color: widget.color,
                  shape: BoxShape.circle,
                ),
              ),
            );
          },
        );
      }),
    );
  }
}
