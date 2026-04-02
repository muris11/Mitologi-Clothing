import 'package:flutter/material.dart';

import '../../../utils/responsive_helper.dart';
import '../../config/theme.dart';
import '../../config/auth_styles.dart';
import '../../utils/navigation_helper.dart';

class AuthPageShell extends StatelessWidget {
  const AuthPageShell({
    super.key,
    required this.title,
    required this.subtitle,
    required this.child,
    this.showBack = true,
    this.sectionLabel = 'Akun Toko',
  });

  final String title;
  final String subtitle;
  final Widget child;
  final bool showBack;
  final String sectionLabel;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: AppTheme.slate50,
      body: SafeArea(
        child: LayoutBuilder(
          builder: (context, constraints) => SingleChildScrollView(
            padding: EdgeInsets.symmetric(
              horizontal: ResponsiveHelper.horizontalPadding(context),
              vertical: 32,
            ),
            child: Center(
              child: ConstrainedBox(
                constraints: const BoxConstraints(maxWidth: 520),
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.stretch,
                  children: [
                    if (showBack)
                      Align(
                        alignment: Alignment.centerLeft,
                        child: IconButton(
                          onPressed: () => context.popOrGoHome(),
                          tooltip: 'Back',
                          icon: const Icon(
                            Icons.arrow_back,
                            color: AppTheme.onSurface,
                          ),
                        ),
                      ),
                    if (showBack) const SizedBox(height: 8),
                    Container(
                      padding: EdgeInsets.all(
                        ResponsiveHelper.horizontalPadding(context),
                      ),
                      decoration: AuthStyles.cardDecoration,
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.stretch,
                        children: [
                          _buildHeader(context),
                          const SizedBox(height: AuthStyles.fieldSpacing),
                          child,
                          const SizedBox(height: 32),
                          _buildFooter(context),
                        ],
                      ),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildHeader(BuildContext context) {
    return Container(
      padding: EdgeInsets.only(
        bottom: ResponsiveHelper.horizontalPadding(context),
      ),
      decoration: const BoxDecoration(
        border: Border(bottom: BorderSide(color: AppTheme.slate100, width: 1)),
      ),
      child: Column(
        children: [
          SizedBox(
            width: 80,
            height: 80,
            child: Image.asset('assets/images/logo.png', fit: BoxFit.contain),
          ),
          const SizedBox(height: 20),
          Text(sectionLabel.toUpperCase(), style: AuthStyles.sectionLabelStyle),
          const SizedBox(height: 12),
          Text(
            title,
            style: AuthStyles.titleStyle,
            textAlign: TextAlign.center,
          ),
          const SizedBox(height: 12),
          Text(
            subtitle,
            style: AuthStyles.subtitleStyle,
            textAlign: TextAlign.center,
          ),
        ],
      ),
    );
  }

  Widget _buildFooter(BuildContext context) {
    return Column(
      children: [
        Container(
          padding: EdgeInsets.only(
            top: ResponsiveHelper.horizontalPadding(context),
          ),
          decoration: const BoxDecoration(
            border: Border(top: BorderSide(color: AppTheme.slate100, width: 1)),
          ),
          child: Text(
            '© ${DateTime.now().year} Mitologi Clothing. Hak cipta dilindungi.',
            style: const TextStyle(
              fontSize: 12,
              color: AppTheme.slate400,
              fontWeight: FontWeight.w500,
            ),
            textAlign: TextAlign.center,
          ),
        ),
      ],
    );
  }
}
