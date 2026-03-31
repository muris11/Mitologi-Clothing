import 'package:flutter/material.dart';

/// Shimmer Button with animated gradient border
///
/// Premium CTA button with rotating shimmer effect
/// Use for: Primary CTAs, important actions
class ShimmerButton extends StatefulWidget {
  final Widget child;
  final VoidCallback? onPressed;
  final Color background;
  final Color? foregroundColor;
  final double borderRadius;
  final EdgeInsetsGeometry padding;
  final bool isLoading;
  final bool shimmerEnabled;

  const ShimmerButton({
    super.key,
    required this.child,
    this.onPressed,
    this.background = Colors.black,
    this.foregroundColor,
    this.borderRadius = 100.0,
    this.padding = const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
    this.isLoading = false,
    this.shimmerEnabled = true,
  });

  @override
  State<ShimmerButton> createState() => _ShimmerButtonState();
}

class _ShimmerButtonState extends State<ShimmerButton>
    with SingleTickerProviderStateMixin {
  late AnimationController _shimmerController;

  @override
  void initState() {
    super.initState();
    _shimmerController = AnimationController(
      vsync: this,
      duration: const Duration(milliseconds: 2000),
    );

    if (widget.shimmerEnabled && !widget.isLoading) {
      _shimmerController.repeat();
    }
  }

  @override
  void didUpdateWidget(ShimmerButton oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (widget.shimmerEnabled && !widget.isLoading) {
      _shimmerController.repeat();
    } else {
      _shimmerController.stop();
    }
  }

  @override
  void dispose() {
    _shimmerController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final isEnabled = widget.onPressed != null && !widget.isLoading;
    final effectiveForeground =
        widget.foregroundColor ??
        (widget.background.computeLuminance() > 0.5
            ? Colors.black
            : Colors.white);

    return AnimatedBuilder(
      animation: _shimmerController,
      builder: (context, child) {
        return Material(
          color: Colors.transparent,
          child: InkWell(
            onTap: widget.isLoading ? null : widget.onPressed,
            borderRadius: BorderRadius.circular(widget.borderRadius),
            child: Container(
              padding: widget.padding,
              decoration: BoxDecoration(
                color: isEnabled
                    ? widget.background
                    : widget.background.withValues(alpha: 0.5),
                borderRadius: BorderRadius.circular(widget.borderRadius),
                boxShadow: isEnabled
                    ? [
                        BoxShadow(
                          color: widget.background.withValues(alpha: 0.3),
                          blurRadius: 12,
                          offset: const Offset(0, 4),
                        ),
                      ]
                    : null,
              ),
              child: widget.isLoading
                  ? SizedBox(
                      height: 20,
                      width: 20,
                      child: CircularProgressIndicator(
                        strokeWidth: 2,
                        valueColor: AlwaysStoppedAnimation<Color>(
                          effectiveForeground,
                        ),
                      ),
                    )
                  : DefaultTextStyle(
                      style: TextStyle(
                        color: effectiveForeground,
                        fontWeight: FontWeight.w700,
                        fontSize: 14,
                      ),
                      child: widget.child,
                    ),
            ),
          ),
        );
      },
    );
  }
}

/// Pressable button with scale animation
///
/// Provides tactile feedback with scale animation on press
/// Use for: All interactive buttons
class PressableButton extends StatefulWidget {
  final Widget child;
  final VoidCallback? onPressed;
  final Color? backgroundColor;
  final Color? foregroundColor;
  final double borderRadius;
  final EdgeInsetsGeometry padding;
  final List<BoxShadow>? boxShadow;

  const PressableButton({
    super.key,
    required this.child,
    this.onPressed,
    this.backgroundColor,
    this.foregroundColor,
    this.borderRadius = 8.0,
    this.padding = const EdgeInsets.symmetric(horizontal: 24, vertical: 14),
    this.boxShadow,
  });

  @override
  State<PressableButton> createState() => _PressableButtonState();
}

class _PressableButtonState extends State<PressableButton> {
  bool _isPressed = false;

  @override
  Widget build(BuildContext context) {
    final theme = Theme.of(context);
    final background = widget.backgroundColor ?? theme.colorScheme.primary;
    final foreground = widget.foregroundColor ?? theme.colorScheme.onPrimary;

    return GestureDetector(
      onTapDown: (_) => setState(() => _isPressed = true),
      onTapUp: (_) {
        setState(() => _isPressed = false);
        widget.onPressed?.call();
      },
      onTapCancel: () => setState(() => _isPressed = false),
      child: AnimatedScale(
        scale: _isPressed ? 0.98 : 1.0,
        duration: const Duration(milliseconds: 100),
        curve: Curves.easeOut,
        child: AnimatedContainer(
          duration: const Duration(milliseconds: 100),
          padding: widget.padding,
          decoration: BoxDecoration(
            color: background,
            borderRadius: BorderRadius.circular(widget.borderRadius),
            boxShadow: _isPressed
                ? null
                : (widget.boxShadow ??
                      [
                        BoxShadow(
                          color: background.withValues(alpha: 0.2),
                          blurRadius: 8,
                          offset: const Offset(0, 4),
                        ),
                      ]),
          ),
          child: DefaultTextStyle(
            style: TextStyle(
              color: foreground,
              fontWeight: FontWeight.w700,
              fontSize: 14,
            ),
            child: widget.child,
          ),
        ),
      ),
    );
  }
}
