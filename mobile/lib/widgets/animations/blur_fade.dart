import 'package:flutter/material.dart';

/// BlurFade animation - Fade in with blur transition
///
/// Matches Next.js scroll-reveal animation
/// Duration: 600ms with easeOutExpo (0.25, 1, 0.5, 1)
///
/// Use for: Content sections, cards, staggered reveals
class BlurFade extends StatefulWidget {
  final Widget child;
  final Duration delay;
  final Duration duration;
  final bool isVisible;
  final VoidCallback? onComplete;

  const BlurFade({
    super.key,
    required this.child,
    this.delay = Duration.zero,
    this.duration = const Duration(milliseconds: 600),
    this.isVisible = true,
    this.onComplete,
  });

  @override
  State<BlurFade> createState() => _BlurFadeState();
}

class _BlurFadeState extends State<BlurFade>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _opacityAnimation;
  // ignore: unused_field
  late Animation<double> _blurAnimation;
  late Animation<Offset> _slideAnimation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: widget.duration);

    // EaseOutExpo curve: cubic-bezier(0.25, 1, 0.5, 1)
    const curve = Cubic(0.25, 1, 0.5, 1);

    _opacityAnimation = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    _blurAnimation = Tween<double>(
      begin: 10.0,
      end: 0.0,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    _slideAnimation = Tween<Offset>(
      begin: const Offset(0, 30),
      end: Offset.zero,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    if (widget.isVisible) {
      _startAnimation();
    }
  }

  void _startAnimation() {
    if (widget.delay > Duration.zero) {
      Future.delayed(widget.delay, () {
        if (mounted) {
          _controller.forward().then((_) {
            widget.onComplete?.call();
          });
        }
      });
    } else {
      _controller.forward().then((_) {
        widget.onComplete?.call();
      });
    }
  }

  @override
  void didUpdateWidget(BlurFade oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (widget.isVisible && !oldWidget.isVisible) {
      _controller.forward();
    } else if (!widget.isVisible && oldWidget.isVisible) {
      _controller.reverse();
    }
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
        return Transform.translate(
          offset: _slideAnimation.value,
          child: Opacity(opacity: _opacityAnimation.value, child: widget.child),
        );
      },
      child: widget.child,
    );
  }
}

/// Staggered list animation
///
/// Animates children with staggered delays
/// Stagger: 100ms between items
class StaggeredList extends StatelessWidget {
  final List<Widget> children;
  final Duration delay;
  final Duration staggerDelay;
  final Axis direction;
  final MainAxisAlignment mainAxisAlignment;
  final CrossAxisAlignment crossAxisAlignment;
  final MainAxisSize mainAxisSize;

  const StaggeredList({
    super.key,
    required this.children,
    this.delay = Duration.zero,
    this.staggerDelay = const Duration(milliseconds: 100),
    this.direction = Axis.vertical,
    this.mainAxisAlignment = MainAxisAlignment.start,
    this.crossAxisAlignment = CrossAxisAlignment.center,
    this.mainAxisSize = MainAxisSize.max,
  });

  @override
  Widget build(BuildContext context) {
    return Flex(
      direction: direction,
      mainAxisAlignment: mainAxisAlignment,
      crossAxisAlignment: crossAxisAlignment,
      mainAxisSize: mainAxisSize,
      children: children.asMap().entries.map((entry) {
        final index = entry.key;
        final child = entry.value;

        return BlurFade(
          delay: delay + (staggerDelay * index),
          duration: const Duration(milliseconds: 600),
          child: child,
        );
      }).toList(),
    );
  }
}

/// Fade in up animation
///
/// Matches Next.js fadeInUp variant
/// Translates from y: 40 to y: 0 with opacity
class FadeInUp extends StatefulWidget {
  final Widget child;
  final Duration delay;
  final Duration duration;
  final double beginOffset;

  const FadeInUp({
    super.key,
    required this.child,
    this.delay = Duration.zero,
    this.duration = const Duration(milliseconds: 600),
    this.beginOffset = 40.0,
  });

  @override
  State<FadeInUp> createState() => _FadeInUpState();
}

class _FadeInUpState extends State<FadeInUp>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _opacityAnimation;
  late Animation<double> _slideAnimation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: widget.duration);

    const curve = Cubic(0.25, 1, 0.5, 1);

    _opacityAnimation = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    _slideAnimation = Tween<double>(
      begin: widget.beginOffset,
      end: 0.0,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    _startAnimation();
  }

  void _startAnimation() {
    if (widget.delay > Duration.zero) {
      Future.delayed(widget.delay, () {
        if (mounted) {
          _controller.forward();
        }
      });
    } else {
      _controller.forward();
    }
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
        return Transform.translate(
          offset: Offset(0, _slideAnimation.value),
          child: Opacity(opacity: _opacityAnimation.value, child: child),
        );
      },
      child: widget.child,
    );
  }
}

/// Scale in animation
///
/// Scales from 0.9 to 1.0 with opacity
/// Use for: Dialogs, modals, important elements
class ScaleIn extends StatefulWidget {
  final Widget child;
  final Duration delay;
  final Duration duration;
  final double beginScale;

  const ScaleIn({
    super.key,
    required this.child,
    this.delay = Duration.zero,
    this.duration = const Duration(milliseconds: 500),
    this.beginScale = 0.9,
  });

  @override
  State<ScaleIn> createState() => _ScaleInState();
}

class _ScaleInState extends State<ScaleIn> with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _opacityAnimation;
  late Animation<double> _scaleAnimation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: widget.duration);

    const curve = Cubic(0.25, 1, 0.5, 1);

    _opacityAnimation = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    _scaleAnimation = Tween<double>(
      begin: widget.beginScale,
      end: 1.0,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    _startAnimation();
  }

  void _startAnimation() {
    if (widget.delay > Duration.zero) {
      Future.delayed(widget.delay, () {
        if (mounted) {
          _controller.forward();
        }
      });
    } else {
      _controller.forward();
    }
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
        return Transform.scale(
          scale: _scaleAnimation.value,
          child: Opacity(opacity: _opacityAnimation.value, child: child),
        );
      },
      child: widget.child,
    );
  }
}

/// Slide in animation
///
/// Slides in from specified direction
class SlideIn extends StatefulWidget {
  final Widget child;
  final Duration delay;
  final Duration duration;
  final AxisDirection direction;
  final double distance;

  const SlideIn({
    super.key,
    required this.child,
    this.delay = Duration.zero,
    this.duration = const Duration(milliseconds: 600),
    this.direction = AxisDirection.right,
    this.distance = 50.0,
  });

  @override
  State<SlideIn> createState() => _SlideInState();
}

class _SlideInState extends State<SlideIn> with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _opacityAnimation;
  late Animation<Offset> _slideAnimation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(vsync: this, duration: widget.duration);

    const curve = Cubic(0.25, 1, 0.5, 1);

    _opacityAnimation = Tween<double>(
      begin: 0.0,
      end: 1.0,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    Offset beginOffset;
    switch (widget.direction) {
      case AxisDirection.up:
        beginOffset = Offset(0, widget.distance);
        break;
      case AxisDirection.down:
        beginOffset = Offset(0, -widget.distance);
        break;
      case AxisDirection.left:
        beginOffset = Offset(widget.distance, 0);
        break;
      case AxisDirection.right:
        beginOffset = Offset(-widget.distance, 0);
        break;
    }

    _slideAnimation = Tween<Offset>(
      begin: beginOffset,
      end: Offset.zero,
    ).animate(CurvedAnimation(parent: _controller, curve: curve));

    _startAnimation();
  }

  void _startAnimation() {
    if (widget.delay > Duration.zero) {
      Future.delayed(widget.delay, () {
        if (mounted) {
          _controller.forward();
        }
      });
    } else {
      _controller.forward();
    }
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
        return Transform.translate(
          offset: _slideAnimation.value,
          child: Opacity(opacity: _opacityAnimation.value, child: child),
        );
      },
      child: widget.child,
    );
  }
}
