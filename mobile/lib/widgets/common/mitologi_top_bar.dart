import 'package:flutter/material.dart';
import 'package:go_router/go_router.dart';

import '../../config/theme.dart';
import '../../utils/responsive_helper.dart';

class MitologiTopBar extends StatelessWidget implements PreferredSizeWidget {
  const MitologiTopBar({
    super.key,
    this.title,
    this.subtitle,
    this.leading,
    this.actions,
    this.showLogo = true,
    this.centerTitle = false,
    this.showNotification = true,
  });

  final String? title;
  final String? subtitle;
  final Widget? leading;
  final List<Widget>? actions;
  final bool showLogo;
  final bool centerTitle;
  final bool showNotification;

  @override
  Size get preferredSize => Size.fromHeight(subtitle == null ? 84 : 98);

  @override
  Widget build(BuildContext context) {
    final horizontalPadding = ResponsiveHelper.horizontalPadding(context);

    return AppBar(
      automaticallyImplyLeading: false,
      toolbarHeight: preferredSize.height,
      titleSpacing: 0,
      centerTitle: centerTitle,
      leading: leading,
      actions: [
        ...?actions,
        if (showNotification)
          Padding(
            padding: const EdgeInsets.only(right: 8),
            child: IconButton(
              icon: Container(
                padding: const EdgeInsets.all(8),
                decoration: BoxDecoration(
                  color: AppTheme.pageBackground,
                  borderRadius: AppTheme.radius12,
                  border: Border.all(color: AppTheme.outline, width: 1),
                ),
                child: const Icon(
                  Icons.notifications_none_outlined,
                  size: 20,
                  color: AppTheme.onSurface,
                ),
              ),
              tooltip: 'Notifications',
              onPressed: () => context.push('/notifications'),
            ),
          ),
      ],
      title: Padding(
        padding: EdgeInsets.only(left: leading == null ? horizontalPadding : 0),
        child: Row(
          children: [
            if (showLogo) ...[_buildLogo(), const SizedBox(width: 14)],
            Expanded(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: centerTitle
                    ? CrossAxisAlignment.center
                    : CrossAxisAlignment.start,
                children: [
                  AnimatedSwitcher(
                    duration: const Duration(milliseconds: 200),
                    child: Text(
                      title ?? 'Mitologi Clothing',
                      key: ValueKey(title),
                      maxLines: 1,
                      overflow: TextOverflow.ellipsis,
                      style: Theme.of(context).textTheme.titleMedium?.copyWith(
                        fontSize: 18,
                        fontWeight: FontWeight.w700,
                        color: AppTheme.primary,
                        letterSpacing: -0.3,
                      ),
                    ),
                  ),
                  if (subtitle != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 2),
                      child: Text(
                        subtitle!,
                        maxLines: 1,
                        overflow: TextOverflow.ellipsis,
                        style: Theme.of(context).textTheme.bodySmall?.copyWith(
                          color: AppTheme.onSurfaceVariant,
                          letterSpacing: 0.2,
                        ),
                      ),
                    ),
                ],
              ),
            ),
          ],
        ),
      ),
      backgroundColor: AppTheme.pageBackground,
      foregroundColor: AppTheme.onSurface,
      surfaceTintColor: Colors.transparent,
      shadowColor: AppTheme.primary.withValues(alpha: 0.04),
      elevation: 0,
    );
  }

  Widget _buildLogo() {
    return Container(
      width: 40,
      height: 40,
      padding: const EdgeInsets.all(6),
      decoration: BoxDecoration(
        color: AppTheme.pageBackground,
        borderRadius: AppTheme.radius12,
        border: Border.all(color: AppTheme.outline, width: 1),
      ),
      child: Image.asset('assets/images/logo.png', fit: BoxFit.contain),
    );
  }
}
