import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import '../../config/theme.dart';

class AnimatedNavIcon extends StatelessWidget {
  final IconData inactiveIcon;
  final IconData activeIcon;
  final String label;
  final bool isSelected;
  final int badgeCount;
  final VoidCallback onTap;
  final Animation<double> animation;

  const AnimatedNavIcon({
    super.key,
    required this.inactiveIcon,
    required this.activeIcon,
    required this.label,
    required this.isSelected,
    required this.onTap,
    required this.animation,
    this.badgeCount = 0,
  });

  @override
  Widget build(BuildContext context) {
    return Expanded(
      child: Material(
        color: Colors.transparent,
        child: InkWell(
          onTap: () {
            HapticFeedback.lightImpact();
            onTap();
          },
          borderRadius: BorderRadius.circular(AppTheme.navPillRadius),
          splashColor: AppTheme.accent.withValues(alpha: 0.12),
          highlightColor: AppTheme.accent.withValues(alpha: 0.06),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 12),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                _buildIconWithBadge(),
                const SizedBox(height: 6),
                _buildLabel(context),
                const SizedBox(height: 4),
                _buildIndicator(),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildIconWithBadge() {
    return Stack(
      clipBehavior: Clip.none,
      children: [
        AnimatedBuilder(
          animation: animation,
          builder: (context, child) {
            return Transform.scale(
              scale: 1.0 + (animation.value * 0.1),
              child: child,
            );
          },
          child: AnimatedSwitcher(
            duration: const Duration(milliseconds: 200),
            switchInCurve: Curves.easeOutCubic,
            switchOutCurve: Curves.easeInCubic,
            child: Icon(
              isSelected ? activeIcon : inactiveIcon,
              key: ValueKey(isSelected),
              color: isSelected ? AppTheme.accent : AppTheme.onSurfaceVariant,
              size: 24,
            ),
          ),
        ),
        if (badgeCount > 0)
          Positioned(right: -6, top: -4, child: _buildBadge()),
      ],
    );
  }

  Widget _buildBadge() {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 5, vertical: 2),
      decoration: BoxDecoration(
        color: AppTheme.error,
        borderRadius: AppTheme.radius11,
        boxShadow: [
          BoxShadow(
            color: AppTheme.error.withValues(alpha: 0.3),
            blurRadius: 4,
            offset: const Offset(0, 1),
          ),
        ],
      ),
      constraints: const BoxConstraints(minWidth: 18),
      child: Text(
        badgeCount > 99 ? '99+' : '$badgeCount',
        style: const TextStyle(
          color: Colors.white,
          fontSize: 10,
          fontWeight: FontWeight.w700,
        ),
        textAlign: TextAlign.center,
      ),
    );
  }

  Widget _buildLabel(BuildContext context) {
    return AnimatedDefaultTextStyle(
      duration: const Duration(milliseconds: 200),
      curve: Curves.easeOutCubic,
      style:
          Theme.of(context).textTheme.labelSmall?.copyWith(
            fontWeight: isSelected ? FontWeight.w600 : FontWeight.w500,
            color: isSelected ? AppTheme.accent : AppTheme.onSurfaceVariant,
            letterSpacing: 0.3,
          ) ??
          TextStyle(
            fontSize: 11,
            fontWeight: isSelected ? FontWeight.w600 : FontWeight.w500,
            color: isSelected ? AppTheme.accent : AppTheme.onSurfaceVariant,
            letterSpacing: 0.3,
          ),
      child: Text(label),
    );
  }

  Widget _buildIndicator() {
    return AnimatedContainer(
      duration: const Duration(milliseconds: 250),
      curve: Curves.easeOutCubic,
      width: isSelected ? 20 : 0,
      height: 3,
      decoration: BoxDecoration(
        color: isSelected ? AppTheme.accent : Colors.transparent,
        borderRadius: BorderRadius.circular(2),
      ),
    );
  }
}

class AnimatedNavIconStateful extends StatefulWidget {
  final IconData inactiveIcon;
  final IconData activeIcon;
  final String label;
  final bool isSelected;
  final int badgeCount;
  final VoidCallback onTap;

  const AnimatedNavIconStateful({
    super.key,
    required this.inactiveIcon,
    required this.activeIcon,
    required this.label,
    required this.isSelected,
    required this.onTap,
    this.badgeCount = 0,
  });

  @override
  State<AnimatedNavIconStateful> createState() =>
      _AnimatedNavIconStatefulState();
}

class _AnimatedNavIconStatefulState extends State<AnimatedNavIconStateful>
    with SingleTickerProviderStateMixin {
  late AnimationController _controller;
  late Animation<double> _animation;

  @override
  void initState() {
    super.initState();
    _controller = AnimationController(
      duration: const Duration(milliseconds: 400),
      vsync: this,
    );
    _animation = CurvedAnimation(parent: _controller, curve: Curves.elasticOut);

    if (widget.isSelected) {
      _controller.value = 1.0;
    }
  }

  @override
  void didUpdateWidget(AnimatedNavIconStateful oldWidget) {
    super.didUpdateWidget(oldWidget);
    if (widget.isSelected && !oldWidget.isSelected) {
      _controller.forward(from: 0.0);
    } else if (!widget.isSelected && oldWidget.isSelected) {
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
    return AnimatedNavIcon(
      inactiveIcon: widget.inactiveIcon,
      activeIcon: widget.activeIcon,
      label: widget.label,
      isSelected: widget.isSelected,
      badgeCount: widget.badgeCount,
      onTap: widget.onTap,
      animation: _animation,
    );
  }
}
